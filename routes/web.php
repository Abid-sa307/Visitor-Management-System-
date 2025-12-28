<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\{
    ProfileController,
    VisitorController,
    UserController,
    CompanyController,
    DepartmentController,
    VisitorCategoryController,
    EmployeeController,
    DashboardController,
    SecurityCheckController,
    ReportController,
    // Auth\CompanyLoginController,
    Auth\CompanyAuthController,
    Auth\OtpVerificationController,
    ApprovalController,
    SettingsController,
    QRManagementController,
    FaceRecognitionController
};
use App\Http\Controllers\Auth\CompanyLoginController;
use App\Http\Middleware\CheckMasterPageAccess;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\OtpVerificationMail;
use App\Http\Middleware\VerifyOtp;


// Test routes
Route::get('/test-db', function() {
    dd(config('database.connections.mysql.username'));
});

// Test notification route
Route::get('/test-notification', function() {
    return view('test-notification');
})->name('test.notification');

// Test email route
Route::get('/test-email', function () {
    try {
        Mail::raw('This is a test email from your application', function($message) {
            $message->to('nntvms@gmail.com')
                    ->subject('Test Email from Visitor Management System');
        });
        return 'Test email sent successfully to nntvms@gmail.com';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

/*
|----------------------------------------------------------------------|
| Public Routes (Unauthenticated Routes)
|----------------------------------------------------------------------|
*/

Route::get('/', fn() => view('welcome'));
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/partner', fn() => view('partner'))->name('partner');
Route::get('/pricing', fn() => view('pricing'))->name('pricing');
Route::get('/contact', fn() => view('contact'))->name('contact');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');


Route::get('/industrial-and-cold-storage', fn() => view('pages.industrial-and-cold-storage'))->name('industrial-and-cold-storage');
Route::get('/school-and-colleges', fn() => view('pages.school-and-colleges'))->name('school-and-colleges');
Route::get('/industrial-manufacturing-unit', fn() => view('pages.industrial-manufacturing-unit'))->name('industrial-manufacturing-unit');
Route::get('/resident-societies', fn() => view('pages.resident-societies'))->name('resident-societies');
Route::get('/resident-buildings', fn() => view('pages.resident-buildings'))->name('resident-buildings');
Route::get('/office-workplace-management', fn() => view('pages.office-workplace-management'))->name('office-workplace-management');
Route::get('/healthcare-facilities', fn() => view('pages.healthcare-facilities'))->name('healthcare-facilities');
Route::get('/malls-and-events', fn() => view('pages.malls-and-events'))->name('malls-and-events');
Route::get('/temple-and-dargah', fn() => view('pages.temple-and-dargah'))->name('temple-and-dargah');
Route::get('/privacy-policy', fn() => view('pages.privacy-policy'))->name('privacy-policy');
Route::get('/terms-of-use', fn() => view('pages.terms-of-use'))->name('terms-of-use');
Route::get('/refund-and-cancellation', fn() => view('pages.refund-and-cancellation'))->name('refund-and-cancellation');
Route::get('/visitor-management-system-in-usa', fn() => view('pages.visitor-management-system-in-usa'))->name('visitor-management-system-in-usa');
Route::get('/visitor-management-system-in-uk', fn() => view('pages.visitor-management-system-in-uk'))->name('visitor-management-system-in-uk');






/*
|--------------------------------------------------------------------------|
| QR Code Management Routes
|--------------------------------------------------------------------------|
*/
// QR Code Management Routes
// In routes/web.php
Route::get('/public/company/{company}/visitor/{visitor}', [QRManagementController::class, 'publicVisitorIndex'])
    ->name('public.visitor.index');

// Branch-specific visitor index
Route::get('/public/company/{company}/branch/{branch}/visitor/{visitor}', [QRManagementController::class, 'publicVisitorIndex'])
    ->name('public.visitor.index.branch');



    
Route::prefix('qr')->name('qr.')->group(function () {
    // Public routes
    Route::get('/scan/{company}/{branch?}', [QRManagementController::class, 'scan'])
        ->name('scan');
        
    Route::get('/{company}/visitor/create', [QRManagementController::class, 'createVisitor'])
        ->name('visitor.create');
        
    Route::post('/{company}/visitor', [QRManagementController::class, 'storeVisitor'])
        ->name('visitor.store');
        
    // Visit form for completing visitor registration
    Route::get('/{company}/visitor/{visitor}/visit', [QRManagementController::class, 'showVisitForm'])
        ->name('visitor.visit.form');
        
    // Handle visit form submission
    Route::post('/{company}/visitor/{visitor}/visit', [QRManagementController::class, 'storeVisit'])
        ->name('visitor.visit.store');
        
    // Protected routes (require authentication)
    Route::middleware('auth')->group(function () {
        // Add any protected routes here
    });
});

/*
|----------------------------------------------------------------------|
| Company Auth Routes
|----------------------------------------------------------------------|
*/
// Company Auth Routes
Route::prefix('company')->name('company.')->group(function () {
    // Guest routes (for non-authenticated users)
    Route::middleware('guest:company')->group(function () {
        Route::get('/login', [CompanyLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [CompanyLoginController::class, 'login'])->name('login.submit');
    });

    // Authenticated routes
    Route::middleware('auth:company')->group(function () {
        Route::post('/logout', [CompanyAuthController::class, 'logout'])->name('logout');
    });
});


// OTP Verification Routes
Route::middleware('web')->group(function () {
    Route::get('/otp/verify', [OtpVerificationController::class, 'show'])->name('otp.verify');
    Route::post('/otp/verify', [OtpVerificationController::class, 'verify'])->name('otp.verify.post');
    Route::post('/otp/resend', [OtpVerificationController::class, 'resend'])->name('otp.resend');
});

/*
|----------------------------------------------------------------------|
| Authenticated Routes (Shared for both Superadmin and Company)
|----------------------------------------------------------------------|
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/companies/{company}/departments', [DepartmentController::class, 'getByCompany'])->name('companies.departments');
    Route::get('/companies/{company}/branches', [CompanyController::class, 'getBranches'])->name('companies.branches');
    Route::get('/visitors/lookup', [VisitorController::class, 'lookupByPhone'])->name('visitors.lookup');

    // Face Recognition Routes
    Route::prefix('face-recognition')->name('face.')->group(function () {
        Route::get('/recognize', [FaceRecognitionController::class, 'recognize'])->name('recognize');
        Route::post('/detect', [FaceRecognitionController::class, 'detect'])->name('detect');
        Route::post('/verify', [FaceRecognitionController::class, 'verify'])->name('verify');
        Route::post('/register', [FaceRecognitionController::class, 'register'])->name('register');
        Route::get('/status/{visitor}', [FaceRecognitionController::class, 'status'])->name('status');
    });
});

/*
|----------------------------------------------------------------------|
| Super Admin Panel Routes (Role: superadmin)
|----------------------------------------------------------------------|
*/
// Shared dashboard route for both superadmins and company users
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/visitors', [VisitorController::class, 'report'])->name('visitors');
        Route::get('/visitors/export', [VisitorController::class, 'reportExport'])->name('visitors.export');
        Route::get('/inout', [VisitorController::class, 'inOutReport'])->name('inout');
        Route::get('/inout/export', [VisitorController::class, 'inOutReportExport'])->name('inout.export');
        Route::get('/security', [VisitorController::class, 'securityReport'])->name('security');
        Route::get('/security/export', [VisitorController::class, 'securityReportExport'])->name('security.export');
        Route::get('/approval', [VisitorController::class, 'approvalReport'])->name('approval');
        Route::get('/approval/export', [VisitorController::class, 'approvalReportExport'])->name('approval.export');
        Route::get('/hourly', [VisitorController::class, 'hourlyReport'])->name('hourly');
        Route::get('/hourly/export', [VisitorController::class, 'hourlyReportExport'])->name('hourly.export');
    });

    // Visitors
    Route::resource('visitors', VisitorController::class)->except(['show']);
    Route::get('/visitors/{visitor}', [VisitorController::class, 'show'])->name('visitors.show');
    Route::get('/visitor-history', [VisitorController::class, 'history'])->name('visitors.history');
    Route::get('/visitor-entry', [VisitorController::class, 'entryPage'])->name('visitors.entry.page');
    Route::post('/visitor-entry-toggle/{id}', [VisitorController::class, 'toggleEntry'])->name('visitors.entry.toggle');
    Route::get('/visitors/{id}/pass', [VisitorController::class, 'printPass'])->name('visitors.pass');
    Route::get('/visitors/{id}/visit', [VisitorController::class, 'visitForm'])->name('visitors.visit.form');
    Route::post('/visitors/{id}/visit', [VisitorController::class, 'submitVisit'])->name('visitors.visit.submit');
    Route::post('/visitors/{visitor}/checkin', [VisitorController::class, 'checkin'])->name('visitors.checkin');
    Route::post('/visitors/{visitor}/checkout', [VisitorController::class, 'checkout'])->name('visitors.checkout');
    
    // Face Recognition
    Route::post('/visitors/{visitor}/verify-face', [FaceRecognitionController::class, 'verifyVisitor'])->name('visitors.verify-face');
    Route::post('/visitors/{visitor}/register-face', [FaceRecognitionController::class, 'registerVisitor'])->name('visitors.register-face');
    Route::post('/visitors/{visitor}/checkin-face', [FaceRecognitionController::class, 'checkInWithFace'])->name('visitors.checkin-face');
    Route::post('/visitors/{visitor}/checkout-face', [FaceRecognitionController::class, 'checkOutWithFace'])->name('visitors.checkout-face');
    
    Route::get('/visitor-approvals', [VisitorController::class, 'approvals'])->name('visitors.approvals');

    // Visits Management
    Route::get('/visits', [VisitorController::class, 'visitsIndex'])->name('visits.index');

    // Face Recognition Management
    Route::prefix('face-management')->name('face.management.')->group(function () {
        Route::get('/', [FaceRecognitionController::class, 'index'])->name('index');
        Route::get('/train', [FaceRecognitionController::class, 'trainModel'])->name('train');
        Route::post('/train', [FaceRecognitionController::class, 'processTraining'])->name('process.training');
    });

    // Resources
    Route::resource('companies', CompanyController::class);
    Route::get('companies/{company}/branches', [CompanyController::class, 'branches'])->name('companies.branches');
    
    // QR Code Management
    Route::prefix('qr-management')->name('qr-management.')->group(function () {
        Route::get('/', [QRManagementController::class, 'index'])->name('index');
        Route::get('/company/{company}', [QRManagementController::class, 'show'])->name('show');
        Route::get('/company/{company}/download/{branch?}', [QRManagementController::class, 'download'])->name('download');
    });
    
    Route::resource('departments', DepartmentController::class);
    Route::resource('users', UserController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('visitor-categories', VisitorCategoryController::class);

    // Security Checks
    Route::prefix('security-checks')->name('security-checks.')->group(function () {
        Route::get('/', [SecurityCheckController::class, 'index'])->name('index');
        Route::get('/create/{visitorId}', [SecurityCheckController::class, 'create'])->name('create');
        Route::get('/checkout/{visitorId}', [SecurityCheckController::class, 'createCheckout'])->name('create-checkout');
        Route::post('/', [SecurityCheckController::class, 'store'])->name('store');
        Route::get('/{securityCheck}', [SecurityCheckController::class, 'show'])->name('show');
        Route::get('/{securityCheck}/print', [SecurityCheckController::class, 'print'])->name('print');
        Route::post('/toggle/{visitor}', [SecurityCheckController::class, 'toggleSecurity'])->name('toggle');
    });
    
    // Security Questions
    Route::resource('security-questions', \App\Http\Controllers\SecurityQuestionController::class);
    Route::get('security-questions/create/checkin', [\App\Http\Controllers\SecurityQuestionController::class, 'createCheckin'])->name('security-questions.create.checkin');
    Route::get('security-questions/create/checkout', [\App\Http\Controllers\SecurityQuestionController::class, 'createCheckout'])->name('security-questions.create.checkout');
});

/*
|----------------------------------------------------------------------|
| Company Panel Routes (Role: company)
|----------------------------------------------------------------------|
*/
Route::prefix('company')
    ->middleware(['auth:company'])
    ->name('company.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'companyDashboard'])->name('dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // Visitors
        Route::resource('visitors', VisitorController::class)->middleware(CheckMasterPageAccess::class . ':visitors');
        Route::get('/visitors/{id}/visit', [VisitorController::class, 'visitForm'])->name('visitors.visit.form');
        Route::post('/visitors/{id}/visit', [VisitorController::class, 'submitVisit'])->name('company.visitors.visit.submit');
        Route::get('/visitor-history', [VisitorController::class, 'history'])->name('visitors.history');
        Route::get('/visitor-entry', [VisitorController::class, 'entryPage'])->name('visitors.entry.page');
        Route::post('/visitor-entry-toggle/{id}', [VisitorController::class, 'toggleEntry'])->name('visitors.entry.toggle');
        Route::get('/visitors/{id}/pass', [VisitorController::class, 'printPass'])->name('visitors.pass');
        
        // Visits Management
        Route::get('/visits', [VisitorController::class, 'visitsIndex'])->name('visits.index');
        
        // Face Recognition
        Route::post('/visitors/{visitor}/verify-face', [FaceRecognitionController::class, 'verifyVisitor'])->name('visitors.verify-face');
        Route::post('/visitors/{visitor}/register-face', [FaceRecognitionController::class, 'registerVisitor'])->name('visitors.register-face');
        Route::post('/visitors/{visitor}/checkin-face', [FaceRecognitionController::class, 'checkInWithFace'])->name('visitors.checkin-face');
        Route::post('/visitors/{visitor}/checkout-face', [FaceRecognitionController::class, 'checkOutWithFace'])->name('visitors.checkout-face');

        // Approvals
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('visitors.approvals');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/visitors', [ReportController::class, 'visitors'])->name('visitors');
            Route::get('/visitors/export', [ReportController::class, 'exportVisitors'])->name('visitors.export');
            Route::get('/visits', [ReportController::class, 'visits'])->name('visits');
            Route::get('/visits/export', [ReportController::class, 'exportVisits'])->name('visits.export');
            Route::get('/security', [ReportController::class, 'securityChecks'])->name('security');
            Route::get('/security/export', [ReportController::class, 'exportSecurityChecks'])->name('security.export');
            Route::get('/approval', [ReportController::class, 'approvals'])->name('approval');
            Route::get('/approval/export', [ReportController::class, 'exportApprovals'])->name('approval.export');
            Route::get('/hourly', [ReportController::class, 'hourlyReport'])->name('hourly');
            Route::get('/hourly/export', [ReportController::class, 'exportHourlyReport'])->name('hourly.export');
        });

        // Resources
        Route::resource('employees', EmployeeController::class)->except(['show']);
        Route::resource('departments', DepartmentController::class)->except(['show']);
        
        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Security Checks
        Route::resource('security-checks', SecurityCheckController::class)->except(['edit', 'update', 'destroy']);
        Route::get('security-checks/{securityCheck}/print', [SecurityCheckController::class, 'print'])->name('security-checks.print');
        Route::post('security-checks/toggle/{visitor}', [SecurityCheckController::class, 'toggleSecurity'])->name('security-checks.toggle');
        
        // Security Questions
        Route::resource('security-questions', \App\Http\Controllers\SecurityQuestionController::class);
    });

/*
|----------------------------------------------------------------------|
| Public Face Recognition Endpoints (for kiosk mode)
|----------------------------------------------------------------------|
*/
Route::prefix('public')->name('public.')->group(function () {
    Route::get('/face-verification', [FaceRecognitionController::class, 'showVerification'])->name('face-verification');
    Route::post('/verify-face', [FaceRecognitionController::class, 'publicVerify'])->name('verify-face');
});

/*
|----------------------------------------------------------------------|
| API Routes
|----------------------------------------------------------------------|
*/
// API Routes for AJAX requests
// QR Code Routes
Route::prefix('companies/{company}')->name('companies.')->group(function () {
    // Public QR code page (with optional branch)
    Route::get('/public-qr', [CompanyController::class, 'showPublicQrPage'])
        ->name('public.qr');
        
    // Public branch-specific QR code page
    Route::get('/branches/{branchId}/public-qr', [CompanyController::class, 'showPublicBranchQrPage'])
        ->name('branches.public.qr');
        
    // Admin QR code page
    Route::middleware(['auth', 'role:superadmin'])->group(function () {
        Route::get('/qr', [CompanyController::class, 'showQrPage'])
            ->name('qr');
            
        // Download QR code
        Route::get('/qr/download', [CompanyController::class, 'downloadQrCode'])
            ->name('qr.download');
    });
        
    // Branch QR code download
    Route::get('/branches/{branch}/qr/download', [CompanyController::class, 'downloadQrCode'])
        ->name('branches.qr.download')
        ->middleware(['auth', 'role:superadmin']);
});

// Test routes (remove in production)
if (app()->environment('local')) {
    // Test route to create a company user
    Route::get('/test/create-company-user', function () {
        $company = \App\Models\Company::first();
        
        if (!$company) {
            $company = \App\Models\Company::create([
                'name' => 'Test Company',
                'email' => 'test@company.com',
                'phone' => '1234567890',
                'address' => '123 Test St',
            ]);
        }
        
        $user = new \App\Models\CompanyUser();
        $user->name = 'Test Company User';
        $user->email = 'test@companyuser.com';
        $user->password = Hash::make('password123');
        $user->company_id = $company->id;
        $user->role = 'company';
        $user->master_pages = ["dashboard", "visitors"];
        $user->save();
        
        return [
            'message' => 'Company user created successfully',
            'user' => $user->toArray(),
            'login_url' => url('/company/login')
        ];
    });
    
    // Test route to manually log in a company user
    Route::get('/test/login-company-user/{email?}', function ($email = 'company@example.com') {
        $user = \App\Models\CompanyUser::where('email', $email)->first();
        
        if (!$user) {
            return 'Company user not found';
        }
        
        // Try to log in the user
        if (Auth::guard('company')->login($user)) {
            return [
                'success' => true,
                'message' => 'Successfully logged in as ' . $user->email,
                'user' => $user->toArray(),
                'session' => session()->all()
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Failed to log in',
            'user' => $user->toArray()
        ];
    });
    
    // Test route to reset company user password
    Route::get('/test/reset-company-password/{email?}', function ($email = 'company@example.com') {
        $user = \App\Models\CompanyUser::where('email', $email)->first();
        
        if (!$user) {
            return 'Company user not found';
        }
        
        $password = 'password123';
        $user->password = Hash::make($password);
        $user->save();
        
        return [
            'email' => $user->email,
            'password' => $password,
            'message' => 'Password has been reset to: ' . $password
        ];
    });
    
    // Debug route to test company guard configuration
    Route::get('/test/auth-config', function () {
        $config = config('auth');
        
        // Get the company guard configuration
        $guardConfig = $config['guards']['company'] ?? null;
        $provider = $guardConfig ? ($config['providers'][$guardConfig['provider']] ?? null) : null;
        
        // Get the model class
        $modelClass = $provider ? $provider['model'] : null;
        
        // Check if the model exists
        $modelExists = $modelClass ? class_exists($modelClass) : false;
        
        // Get the table name from the model
        $tableName = null;
        if ($modelExists) {
            $model = new $modelClass;
            $tableName = $model->getTable();
            
            // Check if table exists
            $tableExists = \Illuminate\Support\Facades\Schema::hasTable($tableName);
        }
        
        return [
            'guard_config' => $guardConfig,
            'provider_config' => $provider,
            'model' => [
                'class' => $modelClass,
                'exists' => $modelExists,
                'table' => $tableName,
                'table_exists' => $tableExists ?? false,
                'columns' => $tableName ? \Illuminate\Support\Facades\Schema::getColumnListing($tableName) : []
            ],
            'auth_config' => [
                'defaults' => $config['defaults'] ?? null,
                'guards' => array_keys($config['guards'] ?? []),
                'providers' => array_keys($config['providers'] ?? [])
            ]
        ];
    });
    // Reset company user password
    Route::get('/test/fix-company-password', function () {
        $user = \App\Models\CompanyUser::where('email', 'company@example.com')->first();
        
        if (!$user) {
            return 'User not found';
        }
        
        $password = 'password123';
        $user->password = $password;
        $user->save();
        
        return [
            'status' => 'success',
            'message' => 'Password reset successfully',
            'email' => $user->email,
            'password' => $password,
            'hashed_password' => $user->password,
            'password_matches' => \Illuminate\Support\Facades\Hash::check($password, $user->password)
        ];
    });
    
    // Create test company user
    Route::get('/test/create-company-user-account', function () {
        // Check if company exists
        $company = \App\Models\Company::first();
        
        if (!$company) {
            return 'Company not found. Please create a company first.';
        }
        
        // Create a company user account
        try {
            $companyUser = \App\Models\CompanyUser::updateOrCreate(
                ['email' => 'company@example.com'],
                [
                    'name' => 'Test Company User',
                    'password' => 'password123', // Will be hashed by the mutator
                    'company_id' => $company->id,
                    'role' => 'company',
                    'master_pages' => []
                ]
            );
            
            return [
                'status' => 'success',
                'message' => 'Company user created successfully',
                'user' => [
                    'id' => $companyUser->id,
                    'name' => $companyUser->name,
                    'email' => $companyUser->email,
                    'company_id' => $companyUser->company_id
                ],
                'login_url' => url('/company/login'),
                'credentials' => [
                    'email' => 'company@example.com',
                    'password' => 'password123'
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error creating company user',
                'error' => $e->getMessage()
            ];
        }
    });
    
    Route::get('/test/create-company-user', function () {
        $company = \App\Models\Company::first();
        
        if (!$company) {
            $company = new \App\Models\Company();
            $company->name = 'Test Company';
            $company->email = 'test@example.com';
            $company->password = \Illuminate\Support\Facades\Hash::make('password123');
            $company->address = '123 Test St';
            $company->contact_number = '1234567890';
            $company->save();
            
            return 'Test company user created!<br>Email: test@example.com<br>Password: password123';
        }
        
        return 'Company already exists. Email: ' . $company->email;
    });
}

Route::prefix('api')->name('api.')->group(function () {
    // Get departments for a company
    Route::get('/companies/{company}/departments', [App\Http\Controllers\DepartmentController::class, 'getByCompany'])->name('departments.by_company')->middleware('web');
    
    // Get branches for a company
    Route::get('/companies/{company}/branches', [App\Http\Controllers\BranchController::class, 'getByCompany'])->name('branches.by_company')->middleware('web');
    
    // Get departments for a branch
    Route::get('/branches/{branch}/departments', [App\Http\Controllers\BranchController::class, 'getDepartments'])->name('departments.by_branch')->middleware('web');
    
    Route::prefix('face')->name('face.')->group(function () {
        Route::post('/detect', [FaceRecognitionController::class, 'apiDetect'])->name('detect');
        Route::post('/verify', [FaceRecognitionController::class, 'apiVerify'])->name('verify');
        Route::post('/register', [FaceRecognitionController::class, 'apiRegister'])->name('register');
        Route::get('/models', [FaceRecognitionController::class, 'getModels'])->name('models');
    });

    Route::get('/visitors', [VisitorController::class, 'apiIndex'])->name('visitors.index');
    Route::get('/visitors/{visitor}', [VisitorController::class, 'apiShow'])->name('visitors.show');
});

// Serve face recognition models
Route::get('/models/{filename}', function ($filename) {
    $path = public_path('models/' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404, 'Model file not found: ' . $filename);
})->where('filename', '.*');

// Public visit routes
Route::prefix('public')->name('public.')->group(function () {
    // Visitor tracking route
    Route::get('/visitors/{visitor}/track', [\App\Http\Controllers\QRController::class, 'trackVisitor'])
        ->name('visitor.track');
    
    // Show public visit form (for new visits)
    Route::get('/companies/{company}/visitors/{visitor}/visit', [\App\Http\Controllers\QRController::class, 'showPublicVisitForm'])
        ->name('visitor.visit.form');
    
    // Handle public visit form submission (for new visits)
    Route::post('/companies/{company}/visitors/{visitor}/visit', [\App\Http\Controllers\QRController::class, 'storePublicVisit'])
        ->name('visitor.visit.store');
    
    // Show edit form for existing visits
    Route::get('/companies/{company}/visitors/{visitor}/edit', [\App\Http\Controllers\QRController::class, 'editPublicVisit'])
        ->name('visitor.visit.edit');
    
    // Handle update for existing visits
    Route::put('/companies/{company}/visitors/{visitor}', [\App\Http\Controllers\QRController::class, 'updatePublicVisit'])
        ->name('visitor.visit.update');
    
    // Show visitor details
    Route::get('/companies/{company}/visitors/{visitor}', [\App\Http\Controllers\QRController::class, 'showPublicVisitor'])
        ->name('visitor.show');
});

// Breeze/Auth Routes
require __DIR__ . '/auth.php';
////////////////robot.txt////////////////////
Route::get('/robots.txt', function () {
    

    $content  = "User-agent: *\n";
    $content .= "Allow: /\n";

    
    $content .= "Sitemap: " . url('/sitemap-0.xml') . "\n";

    return response($content, 200)
        ->header('Content-Type', 'text/plain');
});

//////////////site map///////
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

