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
            
            // Apply branch filter for company users only if not explicitly selecting a different branch
            if (!$request->filled('branch_id') && $authUser->branch_id) {
                $visitorQuery->where('branch_id', $authUser->branch_id);
            }
            
            \Log::info('Security Check Index - Company User Filter', [
                'user_id' => $authUser->id,
                'company_id' => $companyId,
                'user_branch_id' => $authUser->branch_id,
                'request_branch_id' => $request->input('branch_id'),
                'query_sql' => $visitorQuery->toSql(),
                'query_bindings' => $visitorQuery->getBindings()
            ]);
        }

        // Branch filter (override if explicitly selected)
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

        // Get branches based on company selection or user's company
        $branches = [];
        if ($companyId) {
            $branches = \App\Models\Branch::where('company_id', $companyId)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        } elseif (!$isSuper && $authUser) {
            // For non-superadmin, get their assigned branches
            // Get user's assigned branch IDs from the pivot table
            $userBranchIds = $authUser->branches()->pluck('branches.id')->toArray();
            
            if (!empty($userBranchIds)) {
                // Filter branches by user's assigned branches
                $branches = \App\Models\Branch::whereIn('id', $userBranchIds)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->toArray();
            } else {
                // Fallback to single branch if user has branch_id set
                if ($authUser->branch_id) {
                    $branches = \App\Models\Branch::where('id', $authUser->branch_id)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray();
                } else {
                    // If no branches assigned, get all company branches
                    $branches = \App\Models\Branch::where('company_id', $authUser->company_id)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray();
                }
            }
        }

        // Get departments based on company selection or user's company
        $departments = [];
        if ($companyId) {
            $departments = \App\Models\Department::where('company_id', $companyId)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
        } elseif (!$isSuper && $authUser) {
            // For non-superadmin, get their assigned departments
            // Get user's assigned branch IDs from the pivot table
            $userBranchIds = $authUser->branches()->pluck('branches.id')->toArray();
            
            if (!empty($userBranchIds)) {
                // Get user's assigned department IDs from the pivot table
                $userDepartmentIds = $authUser->departments()->pluck('departments.id')->toArray();
                
                if (!empty($userDepartmentIds)) {
                    // Filter departments by user's assigned departments
                    $departments = \App\Models\Department::whereIn('id', $userDepartmentIds)
                        ->where('company_id', $authUser->company_id)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray();
                } else {
                    // Fallback: filter departments by user's assigned branches
                    $departments = \App\Models\Department::whereIn('branch_id', $userBranchIds)
                        ->where('company_id', $authUser->company_id)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray();
                }
            } else {
                // Fallback to single branch if user has branch_id set
                if ($authUser->branch_id) {
                    $departments = \App\Models\Department::where('branch_id', $authUser->branch_id)
                        ->where('company_id', $authUser->company_id)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray();
                } else {
                    // If no branches assigned, get all company departments
                    $departments = \App\Models\Department::where('company_id', $authUser->company_id)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray();
                }
            }
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

    public function create(Request $request, $visitorId)
    {
        $visitor = Visitor::with('company')->findOrFail($visitorId);
        
        // Check if visitor has completed the visit form
        $bypassFormCheck = $request->boolean('access_form', false);

        if (!$this->hasCompletedVisitForm($visitor) && !$bypassFormCheck) {
            return redirect()->back()->with('error', 'Security check can only be performed after the visitor has completed the visit form.');
        }
        
        $securityQuestions = $this->getSecurityQuestions($visitor, 'checkin');
        
        return view('visitors.security', compact('visitor', 'securityQuestions'));
    }

    public function createCheckout(Request $request, $visitorId)
    {
        $visitor = Visitor::with('company')->findOrFail($visitorId);
        
        // Check if visitor has completed the visit form
        $bypassFormCheck = $request->boolean('access_form', false);

        if (!$this->hasCompletedVisitForm($visitor) && !$bypassFormCheck) {
            return redirect()->back()->with('error', 'Security check can only be performed after the visitor has completed the visit form.');
        }
        
        // Check if visitor is checked in (for checkout security check)
        if (!$visitor->in_time) {
            return redirect()->back()->with('error', 'Visitor must be checked in before security check-out.');
        }
        
        // Check if visitor is already checked out
        if ($visitor->out_time) {
            return redirect()->back()->with('error', 'Visitor has already checked out.');
        }
        
        $securityQuestions = $this->getSecurityQuestions($visitor, 'checkout');
        
        return view('security_checks.checkout', compact('visitor', 'securityQuestions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|exists:visitors,id',
            'check_type' => 'required|in:checkin,checkout',
            'questions' => 'nullable|array',
            'responses' => 'nullable|array',
            'security_officer_name' => 'required|string|max:255',
            'officer_badge' => 'nullable|string|max:100',
            'captured_photo' => 'nullable|string',
            'photo_responses' => 'nullable|array',
        ]);

        // Check if visitor has completed the visit form
        $visitor = Visitor::findOrFail($request->visitor_id);
        $bypassFormCheck = $request->boolean('access_form', false);

        if (!$this->hasCompletedVisitForm($visitor) && !$bypassFormCheck) {
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
                'check_type' => $request->check_type,
                'questions' => $request->questions ?? [],
                'responses' => $request->responses ?? [],
                'security_officer_name' => $request->security_officer_name,
            ]);

            // Update visitor's photo if it's a new one and visitor doesn't have a photo
            $visitor = Visitor::find($request->visitor_id);
            if ($visitor) {
                if (!$visitor->photo && $visitorPhotoPath) {
                    $visitor->photo = $visitorPhotoPath;
                }
                // Set security check time based on check type
                if ($request->check_type === 'checkout') {
                    $visitor->security_checkout_time = now();
                } else {
                    $visitor->security_checkin_time = now();
                }
                $visitor->save();
            }

            // Redirect based on check type
            if ($request->check_type === 'checkout') {
                // For check-out, redirect back to entry page with success message
                return redirect()->route('visitors.entry.page')
                    ->with('success', 'Security check-out completed successfully. You can now mark out the visitor.')
                    ->with('show_undo_security_checkout', true)
                    ->with('security_checkout_id', $securityCheck->id);
            } else {
                // For check-in, redirect to security checks index
                return redirect()->route('security-checks.index')
                    ->with('success', 'Security check completed successfully.');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error saving security check: ' . $e->getMessage());
        }
    }
    
    /**
     * Get security questions for a visitor based on company/branch and check type
     */
    private function getSecurityQuestions($visitor, $checkType = 'checkin')
    {
        $query = \App\Models\SecurityQuestion::query();
        
        // Filter by company if visitor has a company
        if ($visitor->company) {
            $query->where('company_id', $visitor->company->id);
        }
        
        // Filter by branch if visitor has a branch
        if ($visitor->branch) {
            $query->where(function($q) use ($visitor) {
                $q->where('branch_id', $visitor->branch->id)
                  ->orWhereNull('branch_id'); // Include company-wide questions
            });
        }
        
        // Filter by check type
        $query->where(function($q) use ($checkType) {
            $q->where('check_type', $checkType)
              ->orWhere('check_type', 'both');
        });
        
        return $query->where('is_active', true)->get();
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
               !empty($visitor->purpose);
    }
    
    /**
     * Toggle security check-in/check-out for a visitor
     */
    public function toggleSecurity(Request $request, $visitorId)
    {
        $visitor = Visitor::findOrFail($visitorId);
        $action = $request->input('action', 'checkin');
        
        // Debug logging
        \Log::info('toggleSecurity called', [
            'visitor_id' => $visitorId,
            'action' => $action,
            'visitor_status' => $visitor->status,
            'visit_form_completed' => $this->hasCompletedVisitForm($visitor),
            'company_id' => $visitor->company_id,
            'company_security_check_service' => $visitor->company ? $visitor->company->security_check_service : 'no company',
            'company_security_checkin_type' => $visitor->company ? $visitor->company->security_checkin_type : 'no company',
            'security_checks_exist' => $visitor->securityChecks()->exists(),
        ]);
        
        // Check if visitor has completed visit form (required step before security check)
        if (!$this->hasCompletedVisitForm($visitor)) {
            \Log::info('Visit form not completed, returning error');
            return redirect()->back()->with('error', 'Security check can only be performed after the visitor has completed the visit form.');
        }

        // For check-in, ensure visitor is approved and has visit form
        if ($action === 'checkin') {
            if ($visitor->status !== 'Approved') {
                \Log::info('Visitor not approved, returning error');
                return redirect()->back()->with('error', 'Visitor must be approved before security check-in.');
            }
            
            // Check if security form is required based on company settings
            $requiresSecurityForm = false;
            if ($visitor->company && $visitor->company->security_check_service) {
                $securityType = $visitor->company->security_checkin_type;
                \Log::info('Security check enabled, type: ' . $securityType);
                
                // Check if security form is required for this action
                if (in_array($securityType, ['checkin', 'both']) && !$visitor->securityChecks()->exists()) {
                    $requiresSecurityForm = true;
                    \Log::info('Security form required for check-in');
                }
            }
            
            if ($requiresSecurityForm) {
                \Log::info('Redirecting to security form');
                $routeName = $request->routeIs('company.*') ? 'company.security-checks.create' : 'security-checks.create';
                return redirect()->route($routeName, [
                    'visitorId' => $visitor->id,
                    'access_form' => 1,
                ]);
            }
        }
        
        // For check-out, ensure visitor has checked in first
        if ($action === 'checkout') {
            if (!$visitor->in_time) {
                return redirect()->back()->with('error', 'Visitor must check in before security check-out.');
            }
            
            // For checkout, if security checks are enabled and no security check exists, send to security form
            if ($visitor->company && $visitor->company->security_check_service && $visitor->securityChecks()->doesntExist()) {
                $routeName = $request->routeIs('company.*') ? 'company.security-checks.create' : 'security-checks.create';
                return redirect()->route($routeName, [
                    'visitorId' => $visitor->id,
                    'access_form' => 1,
                ]);
            }
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
                // Delete the security check record to allow fresh form
                $visitor->securityChecks()->delete();
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
