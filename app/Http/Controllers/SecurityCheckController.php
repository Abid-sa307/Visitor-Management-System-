<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\SecurityCheck;
use App\Models\Visitor;
use App\Models\Department;
use App\Services\GoogleNotificationService;
use Illuminate\Support\Str;

class SecurityCheckController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        $isCompany = $authUser && in_array($authUser->role, ['company', 'company_user'], true);
        $isSuper = $authUser && in_array($authUser->role, ['super_admin', 'superadmin'], true);

        // Base query - only show visitors from companies that have security checks enabled
        $visitorQuery = Visitor::query()
            ->with(['company', 'department', 'branch'])
            // ->whereHas('company', function($query) {
            //     // Only show visitors from companies with security check service enabled
            //     $query->where('security_check_service', true);
            // })
            ->latest('created_at');

        // Apply date range filter if provided
        $fromDate = $request->input('from');
        $toDate = $request->input('to');
        
        if ($fromDate) {
            $visitorQuery->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $visitorQuery->whereDate('created_at', '<=', $toDate);
        }

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
            
            // Apply branch filter for company users
            if (!$request->filled('branch_id')) {
                // Collect all allowed branch IDs
                $allowedBranchIds = collect();
                if ($authUser->branch_id) $allowedBranchIds->push($authUser->branch_id);
                if ($authUser->branches()->exists()) {
                    $allowedBranchIds = $allowedBranchIds->merge($authUser->branches()->pluck('branches.id'));
                }
                $allowedBranchIds = $allowedBranchIds->unique()->filter()->values();
                
                if ($allowedBranchIds->isNotEmpty()) {
                    $visitorQuery->whereIn('branch_id', $allowedBranchIds);
                }
            }
            
            \Log::info('Security Check Index - Company User Filter', [
                'user_id' => $authUser->id,
                'company_id' => $companyId,
                'allowed_branches' => $allowedBranchIds ?? 'all',
                'request_branch_id' => $request->input('branch_id'),
            ]);
        }

        // Branch filter (override if explicitly selected)
        if ($request->filled('branch_id')) {
            $branchIds = is_array($request->input('branch_id')) ? $request->input('branch_id') : [$request->input('branch_id')];
            $visitorQuery->whereIn('branch_id', $branchIds);
        }

        // Department filter
        if ($request->filled('department_id')) {
            $departmentIds = is_array($request->input('department_id')) ? $request->input('department_id') : [$request->input('department_id')];
            $visitorQuery->whereIn('department_id', $departmentIds);
        }

        $visitors = $visitorQuery->paginate(10)->appends($request->query());

        // Get companies for superadmin dropdown
        $companies = [];
        if ($isSuper) {
            $companies = \App\Models\Company::orderBy('name')->pluck('name', 'id')->toArray();
        }

        // Get branches based on company selection or user's company


    // Get branches based on company selection or user's company
    $branches = [];
    $departments = [];

    if ($companyId) {
        $branches = \App\Models\Branch::where('company_id', $companyId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
            
        $departments = \App\Models\Department::where('company_id', $companyId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    } elseif (!$isSuper && $authUser) {
        // Collect all assigned branch IDs
        $userBranchIds = collect();
        if ($authUser->branch_id) $userBranchIds->push($authUser->branch_id);
        if ($authUser->branches()->exists()) $userBranchIds = $userBranchIds->merge($authUser->branches()->pluck('branches.id'));
        $userBranchIds = $userBranchIds->unique()->filter()->values()->toArray();

        // Populate Branches
        if (!empty($userBranchIds)) {
             $branches = \App\Models\Branch::whereIn('id', $userBranchIds)->orderBy('name')->pluck('name', 'id')->toArray();
        } else {
             $branches = \App\Models\Branch::where('company_id', $authUser->company_id)->orderBy('name')->pluck('name', 'id')->toArray();
        }

        // Populate Departments based on branch selection or assignments
        $deptQuery = \App\Models\Department::where('company_id', $authUser->company_id);
        
        // Filter departments by selected branches in request
        if ($request->filled('branch_id')) {
             $selectedBranchIds = is_array($request->branch_id) ? $request->branch_id : [$request->branch_id];
             $deptQuery->whereIn('branch_id', $selectedBranchIds);
        } elseif (!empty($userBranchIds)) {
             // If no specific branch selected, limit to user's assigned branches
             $deptQuery->whereIn('branch_id', $userBranchIds);
        }

        // Filter by user's specifically assigned departments
        if ($authUser->departments()->exists()) {
             $deptQuery->whereIn('id', $authUser->departments()->pluck('departments.id'));
        }

        $departments = $deptQuery->orderBy('name')->pluck('name', 'id')->toArray();
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
                $routeName = $request->routeIs('company.*') ? 'company.security-checks.index' : 'security-checks.index';
                return redirect()->route($routeName)
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
        
        // Helper to return response based on request type
        $respond = function($success, $message, $redirectUrl = null) use ($request) {
            if ($request->ajax() || $request->wantsJson()) {
                $data = ['success' => $success, 'message' => $message];
                if ($redirectUrl) $data['redirect_url'] = $redirectUrl;
                return response()->json($data);
            }
            
            if ($redirectUrl) return redirect($redirectUrl);
            return redirect()->back()->with($success ? 'success' : 'error', $message);
        };

        // Debug logging
        \Log::info('toggleSecurity called', [
            'visitor_id' => $visitorId,
            'action' => $action,
            'visitor_status' => $visitor->status,
            'visit_form_completed' => $this->hasCompletedVisitForm($visitor),
        ]);
        
        // Check if visitor has completed visit form (required step before security check)
        if (!$this->hasCompletedVisitForm($visitor)) {
            return $respond(false, 'Security check can only be performed after the visitor has completed the visit form.');
        }

        // For check-in, ensure visitor is approved and has visit form
        if ($action === 'checkin') {
            if ($visitor->status !== 'Approved') {
                return $respond(false, 'Visitor must be approved before security check-in.');
            }
            
            // Check if security form is required based on company settings
            $requiresSecurityForm = false;
            if ($visitor->company && $visitor->company->security_check_service) {
                $securityType = $visitor->company->security_checkin_type;
                if (in_array($securityType, ['checkin', 'both']) && !$visitor->securityChecks()->exists()) {
                    $requiresSecurityForm = true;
                }
            }
            
            if ($requiresSecurityForm) {
                $routeName = $request->routeIs('company.*') ? 'company.security-checks.create' : 'security-checks.create';
                $url = route($routeName, ['visitorId' => $visitor->id, 'access_form' => 1]);
                return $respond(true, 'Redirecting to security form...', $url);
            }
        }
        
        // For check-out, ensure visitor has checked in first
        if ($action === 'checkout') {
            if (!$visitor->in_time) {
                return $respond(false, 'Visitor must check in before security check-out.');
            }
            
            // For checkout, if security checks are enabled and no security check exists, send to security form
            if ($visitor->company && $visitor->company->security_check_service && $visitor->securityChecks()->doesntExist()) {
                $routeName = $request->routeIs('company.*') ? 'company.security-checks.create' : 'security-checks.create';
                $url = route($routeName, ['visitorId' => $visitor->id, 'access_form' => 1]);
                return $respond(true, 'Redirecting to security form...', $url);
            }
        }
        
        try {
            if ($action === 'checkin') {
                $visitor->security_checkin_time = now();
                $message = 'Visitor security check-in completed successfully.';
                
                // Send notification if enabled
                try {
                    $notificationService = new GoogleNotificationService();
                    $notificationService->sendSecurityCheckInNotification($visitor->company, $visitor);
                } catch (\Throwable $e) {
                    \Log::error('Failed to send security check-in notification: ' . $e->getMessage());
                }
            } elseif ($action === 'checkout') {
                $visitor->security_checkout_time = now();
                $message = 'Visitor security check-out completed successfully.';
                
                // Send notification if enabled
                try {
                    $notificationService = new GoogleNotificationService();
                    $notificationService->sendSecurityCheckOutNotification($visitor->company, $visitor);
                } catch (\Throwable $e) {
                    \Log::error('Failed to send security check-out notification: ' . $e->getMessage());
                }
            } elseif ($action === 'undo_checkin') {
                if (!$visitor->security_checkin_time || \Carbon\Carbon::parse($visitor->security_checkin_time)->diffInMinutes(now()) > 30) {
                    return $respond(false, 'Undo is only available within 30 minutes of security check-in.');
                }
                $visitor->security_checkin_time = null;
                // Delete the security check record to allow fresh form
                $visitor->securityChecks()->delete();
                $message = 'Security check-in has been undone successfully.';
            } elseif ($action === 'undo_checkout') {
                if (!$visitor->security_checkout_time || \Carbon\Carbon::parse($visitor->security_checkout_time)->diffInMinutes(now()) > 30) {
                    return $respond(false, 'Undo is only available within 30 minutes of security check-out.');
                }
                $visitor->security_checkout_time = null;
                $message = 'Security check-out has been undone successfully.';
            }
            
            $visitor->save();
            
            return $respond(true, $message);
            
        } catch (\Exception $e) {
            return $respond(false, 'An error occurred: ' . $e->getMessage());
        }
    }
}
