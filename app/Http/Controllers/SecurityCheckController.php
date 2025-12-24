<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\SecurityCheck;
use App\Models\Visitor;
use App\Models\Department;
use Illuminate\Support\Str;

class SecurityCheckController extends Controller
{
    public function index(Request $request)
    {
        $isCompany = Auth::guard('company')->check();
        $authUser = $isCompany ? Auth::guard('company')->user() : Auth::user();
        $isSuper = $authUser && in_array($authUser->role, ['super_admin', 'superadmin'], true);

        // Base query
        $visitorQuery = Visitor::query()->with(['company', 'department', 'branch'])->latest('created_at');

        // Apply date range filter if provided
        $fromDate = $request->input('from') ?: now()->subDays(30)->format('Y-m-d');
        $toDate = $request->input('to') ?: now()->format('Y-m-d');
        $visitorQuery->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate);

        // Company filter (for superadmin)
        $companyId = null;
        if ($isSuper) {
            $companyId = $request->input('company_id');
            if ($companyId) {
                $visitorQuery->where('company_id', $companyId);
            }
        } elseif ($isCompany && $authUser) {
            $companyId = $authUser->company_id;
            $visitorQuery->where('company_id', $companyId);
        }

        // Branch filter
        if ($request->filled('branch_id')) {
            $visitorQuery->where('branch_id', $request->input('branch_id'));
        }

        // Department filter
        if ($request->filled('department_id')) {
            $visitorQuery->where('department_id', $request->input('department_id'));
        }

        $visitors = $visitorQuery->paginate(10)->appends($request->query());

        // Get companies for superadmin dropdown
        $companies = [];
        if ($isSuper) {
            $companies = \App\Models\Company::orderBy('name')->pluck('name', 'id')->toArray();
        }

        // Get branches based on company selection
        $branches = [];
        if ($companyId) {
            $branches = \App\Models\Branch::where('company_id', $companyId)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }

        // Get departments based on company selection
        $departments = [];
        if ($companyId) {
            $departments = \App\Models\Department::where('company_id', $companyId)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        }

        return view('security_checks.index', [
            'visitors' => $visitors,
            'departments' => $departments,
            'branches' => $branches,
            'companies' => $companies,
            'isSuper' => $isSuper,
            'isCompany' => $isCompany,
            'from' => $fromDate,
            'to' => $toDate,
        ]);
    }

    public function create($visitorId)
    {
        $visitor = Visitor::with('company')->findOrFail($visitorId);
        
        // Check if visitor has completed the visit form
        if (!$this->hasCompletedVisitForm($visitor)) {
            return redirect()->back()->with('error', 'Security check can only be performed after the visitor has completed the visit form.');
        }
        
        return view('visitors.security', compact('visitor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|exists:visitors,id',
            'questions' => 'required|array',
            'responses' => 'required|array',
            'security_officer_name' => 'required|string|max:255',
            'officer_badge' => 'nullable|string|max:100',
            'captured_photo' => 'nullable|string',
            'photo_responses' => 'nullable|array',
        ]);

        // Check if visitor has completed the visit form
        $visitor = Visitor::findOrFail($request->visitor_id);
        if (!$this->hasCompletedVisitForm($visitor)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Security check can only be performed after the visitor has completed the visit form.');
        }

        try {
            // Process and save the visitor photo if provided
            $visitorPhotoPath = null;
            if ($request->captured_photo) {
                $visitorPhotoPath = $this->saveBase64Image($request->captured_photo, 'visitor_photos');
            }
            
            // Process photo responses if any
            $photoResponses = [];
            if ($request->has('photo_responses')) {
                foreach ($request->photo_responses as $index => $photoData) {
                    if ($photoData) {
                        $photoPath = $this->saveBase64Image($photoData, 'question_photos');
                        $photoResponses[$index] = $photoPath;
                    }
                }
            }

            // Create the security check record
            $securityCheck = SecurityCheck::create([
                'visitor_id' => $request->visitor_id,
                'questions' => $request->questions,
                'responses' => $request->responses,
                'security_officer_name' => $request->security_officer_name,
            ]);

            // Update visitor's photo if it's a new one and visitor doesn't have a photo
            $visitor = Visitor::find($request->visitor_id);
            if ($visitor && !$visitor->photo && $visitorPhotoPath) {
                $visitor->photo = $visitorPhotoPath;
                $visitor->save();
            }

            return redirect()->route('security-checks.show', $securityCheck->id)
                ->with('success', 'Security check completed successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error saving security check: ' . $e->getMessage());
        }
    }
    
    public function show($id)
    {
        $securityCheck = SecurityCheck::with('visitor')->findOrFail($id);
        return view('security_checks.show', compact('securityCheck'));
    }
    
    /**
     * Save a base64 encoded image to storage
     */
    /**
     * Display the printable version of the security check
     */
    public function print($id)
    {
        $securityCheck = SecurityCheck::with('visitor')->findOrFail($id);
        
        // Check if the current user is authorized to view this security check
        $user = Auth::user();
        $isCompany = Auth::guard('company')->check();
        
        if ($isCompany) {
            // For company users, verify they can only see their own visitors
            $companyId = $user->company_id;
            if ($securityCheck->visitor->company_id !== $companyId) {
                abort(403, 'Unauthorized action.');
            }
        } elseif ($user && !$user->hasRole('superadmin') && !$user->hasRole('admin')) {
            // For regular users, check if they have permission
            abort_if(!$user->can('view security checks'), 403);
        }
        
        return view('security_checks.print', compact('securityCheck'));
    }
    
    /**
     * Save a base64 encoded image to storage
     */
    private function saveBase64Image($base64Data, $directory)
    {
        // Extract the image data from the base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $data = substr($base64Data, strpos($base64Data, ',') + 1);
            $type = strtolower($type[1]);
            
            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                throw new \Exception('Invalid image type');
            }
            
            $data = base64_decode($data);
            
            if ($data === false) {
                throw new \Exception('Base64 decode failed');
            }
        } else {
            throw new \Exception('Invalid image data');
        }
        
        // Generate a unique filename
        $filename = Str::random(20) . '.' . $type;
        $path = $directory . '/' . $filename;
        
        // Create the directory if it doesn't exist
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // Save the file to storage
        Storage::disk('public')->put($path, $data);
        
        // For signatures, we might want to make them transparent
        if ($directory === 'signatures') {
            $img = Image::make(storage_path('app/public/' . $path));
            $img->opacity(100);
            $img->save(storage_path('app/public/' . $path));
        }
        
        return $path;
    }
    
    /**
     * Check if visitor has completed the visit form
     */
    private function hasCompletedVisitForm($visitor)
    {
        // Check if essential visit form fields are filled
        return !empty($visitor->department_id) && 
               !empty($visitor->person_to_visit) && 
               !empty($visitor->purpose);
    }
    
    /**
     * Toggle security check-in/check-out for a visitor
     */
    public function toggleSecurity(Request $request, $visitorId)
    {
        $visitor = Visitor::findOrFail($visitorId);
        $action = $request->input('action', 'checkin');
        
        // Check if visitor has completed visit form
        if (!$this->hasCompletedVisitForm($visitor)) {
            return redirect()->back()->with('error', 'Security check can only be performed after the visitor has completed the visit form.');
        }
        
        try {
            if ($action === 'checkin') {
                $visitor->security_checkin_time = now();
                $message = 'Visitor security check-in completed successfully.';
            } elseif ($action === 'checkout') {
                $visitor->security_checkout_time = now();
                $message = 'Visitor security check-out completed successfully.';
            } elseif ($action === 'undo_checkin') {
                if (!$visitor->security_checkin_time || \Carbon\Carbon::parse($visitor->security_checkin_time)->diffInMinutes(now()) > 30) {
                    return redirect()->back()->with('error', 'Undo is only available within 30 minutes of security check-in.');
                }
                $visitor->security_checkin_time = null;
                $message = 'Security check-in has been undone successfully.';
            } elseif ($action === 'undo_checkout') {
                if (!$visitor->security_checkout_time || \Carbon\Carbon::parse($visitor->security_checkout_time)->diffInMinutes(now()) > 30) {
                    return redirect()->back()->with('error', 'Undo is only available within 30 minutes of security check-out.');
                }
                $visitor->security_checkout_time = null;
                $message = 'Security check-out has been undone successfully.';
            }
            
            $visitor->save();
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
