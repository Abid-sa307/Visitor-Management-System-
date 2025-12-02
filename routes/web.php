<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
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
    Auth\CompanyLoginController,
    Auth\CompanyAuthController,
    ApprovalController,
    SettingsController,
    BlogController,
    QRManagementController,
    FaceRecognitionController,
    Auth\OtpVerificationController
};
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\OtpVerificationMail;
use App\Http\Middleware\CheckMasterPageAccess;

// Test routes
Route::get('/test-db', function() {
    dd(config('database.connections.mysql.username'));
});

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


Route::get('/industrial-and-cold-storage', function () {
    return view('pages.industrial-and-cold-storage');
})->name(name: 'industrial-and-cold-storage');
Route::get('/school-and-colleges', function () {
    return view('pages.school-and-colleges');
})->name(name: 'school-and-colleges');
Route::get('/industrial-manufacturing-unit', function () {
    return view('pages.industrial-manufacturing-unit');
})->name(name: 'industrial-manufacturing-unit');
Route::get('/resident-societies', function () {
    return view('pages.resident-societies');
})->name(name: 'resident-societies');
Route::get('/resident-buildings', function () {
    return view('pages.resident-buildings');
})->name(name: 'resident-buildings');
Route::get('/office-workplace-management', function () {
    return view('pages.office-workplace-management');
})->name(name: 'office-workplace-management');
Route::get('/healthcare-facilities', function () {
    return view('pages.healthcare-facilities');
})->name(name: 'healthcare-facilities');
Route::get('/malls-and-events', function () {
    return view('pages.malls-and-events');
})->name(name: 'malls-and-events');
Route::get('/temple-and-dargah', function () {
    return view('pages.temple-and-dargah');
})->name(name: 'temple-and-dargah');
Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('privacy-policy');
Route::get('/terms-of-use', function () {
    return view('pages.terms-of-use');
})->name('terms-of-use');
Route::get('/refund-and-cancellation', function () {
    return view('pages.refund-and-cancellation');
})->name('refund-and-cancellation');
Route::get('/service-agreement', function () {
    return view('pages.service-agreement');
})->name('service-agreement');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');



/*
|--------------------------------------------------------------------------
| Public Routes (Unauthenticated Routes)
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/partner', fn () => view('partner'))->name('partner');
Route::get('/pricing', fn () => view('pricing'))->name('pricing');
Route::get('/contact', fn () => view('contact'))->name('contact');

Route::get('/industrial-and-cold-storage', fn () => view('pages.industrial-and-cold-storage'))
    ->name('industrial-and-cold-storage');
Route::get('/school-and-colleges', fn () => view('pages.school-and-colleges'))
    ->name('school-and-colleges');
Route::get('/industrial-manufacturing-unit', fn () => view('pages.industrial-manufacturing-unit'))
    ->name('industrial-manufacturing-unit');
Route::get('/resident-societies', fn () => view('pages.resident-societies'))
    ->name('resident-societies');
Route::get('/resident-buildings', fn () => view('pages.resident-buildings'))
    ->name('resident-buildings');
Route::get('/office-workplace-management', fn () => view('pages.office-workplace-management'))
    ->name('office-workplace-management');
Route::get('/healthcare-facilities', fn () => view('pages.healthcare-facilities'))
    ->name('healthcare-facilities');
Route::get('/malls-and-events', fn () => view('pages.malls-and-events'))
    ->name('malls-and-events');

Route::get('/privacy-policy', fn () => view('pages.privacy-policy'))->name('privacy-policy');
Route::get('/terms-of-use', fn () => view('pages.terms-of-use'))->name('terms-of-use');
Route::get('/refund-and-cancellation', fn () => view('pages.refund-and-cancellation'))->name('refund-and-cancellation');
Route::get('/service-agreement', fn () => view('pages.service-agreement'))->name('service-agreement');



// QR Code Routes
Route::prefix('qr')->name('qr.')->group(function () {
    // Public scan page (no auth required)
    Route::get('/scan/{company}/{branch?}', [\App\Http\Controllers\QRController::class, 'scan'])
        ->name('scan');
        
    // Public visitor creation form (no auth required)
    Route::get('/{company}/visitor/create', [\App\Http\Controllers\QRController::class, 'createVisitor'])
        ->name('visitor.create');
        
    // Store new visitor (no auth required)
    Route::post('/{company}/visitor', [\App\Http\Controllers\QRController::class, 'storeVisitor'])
        ->name('visitor.store');
        
    // Public visit form (no auth required)
    Route::get('/{company}/visit/{branch?}', [\App\Http\Controllers\QRController::class, 'showVisitForm'])
        ->name('visit');
        
    // Download QR code
    Route::get('/{company}/download', [\App\Http\Controllers\QRController::class, 'downloadQR'])
        ->name('download');
});


/*
|----------------------------------------------------------------------|
| Company Auth Routes
|----------------------------------------------------------------------|
*/
Route::get('/company/login', [CompanyLoginController::class, 'showLoginForm'])->name('company.login');
Route::post('/company/login', [CompanyLoginController::class, 'login'])->name('company.login.custom');
Route::post('/company/logout', [CompanyAuthController::class, 'logout'])->name('company.logout');

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
    // AJAX: Get departments for a company
    Route::get('/companies/{company}/departments', [DepartmentController::class, 'getByCompany'])
        ->name('companies.departments');

    // AJAX: Get branches for a company
    Route::get('/companies/{company}/branches', [CompanyController::class, 'getBranches'])
        ->name('companies.branches');

    // AJAX: Lookup visitor by phone
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
Route::middleware(['auth', 'verified', 'role:superadmin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reports - Must come before resource to avoid conflicts
    Route::prefix('reports')->name('reports.')->group(function () {
        // Main Visitor Report
        Route::get('/visitors', [VisitorController::class, 'report'])->name('visitors');
        Route::get('/visitors/export', [VisitorController::class, 'reportExport'])->name('visitors.export');
        
        // In/Out Report
        Route::get('/inout', [VisitorController::class, 'inOutReport'])->name('inout');
        Route::get('/inout/export', [VisitorController::class, 'inOutReportExport'])->name('inout.export');
        
        // Security Checkpoints Report
        Route::get('/security', [VisitorController::class, 'securityReport'])->name('security');
        Route::get('/security/export', [VisitorController::class, 'securityReportExport'])->name('security.export');
        
        // Approval Status Report
        Route::get('/approval', [VisitorController::class, 'approvalReport'])->name('approval');
        Route::get('/approval/export', [VisitorController::class, 'approvalReportExport'])->name('approval.export');
        
        // Hourly Report
        Route::get('/hourly', [VisitorController::class, 'hourlyReport'])->name('hourly');
        Route::get('/hourly/export', [VisitorController::class, 'hourlyReportExport'])->name('hourly.export');
    });

    // Visitors
    Route::resource('visitors', VisitorController::class)->except(['show']);
    
    // Handle old report URL redirection
    Route::get('/visitors/report', function () {
        return redirect()->route('reports.visitors');
    });
    
    Route::get('/visitors/{visitor}', [VisitorController::class, 'show'])->name('visitors.show');
    Route::get('/visitor-history', [VisitorController::class, 'history'])->name('visitors.history');
    Route::get('/visitor-entry', [VisitorController::class, 'entryPage'])->name('visitors.entry.page');
    Route::post('/visitor-entry-toggle/{id}', [VisitorController::class, 'toggleEntry'])->name('visitors.entry.toggle');
    Route::get('/visitors/{id}/pass', [VisitorController::class, 'printPass'])->name('visitors.pass');
    Route::get('/visitors/{id}/visit', [VisitorController::class, 'visitForm'])->name('visitors.visit.form');
    Route::post('/visitors/{id}/visit', [VisitorController::class, 'submitVisit'])->name('visitors.visit.submit');
    Route::post('/visitors/{visitor}/checkin', [VisitorController::class, 'checkin'])->name('visitors.checkin');
    Route::post('/visitors/{visitor}/checkout', [VisitorController::class, 'checkout'])->name('visitors.checkout');
    Route::get('/visitor-approvals', [VisitorController::class, 'approvals'])->name('visitors.approvals');

    // Face Recognition Management
    Route::prefix('face-management')->name('face.management.')->group(function () {
        Route::get('/', [FaceRecognitionController::class, 'index'])->name('index');
        Route::get('/train', [FaceRecognitionController::class, 'trainModel'])->name('train');
        Route::post('/train', [FaceRecognitionController::class, 'processTraining'])->name('process.training');
    });

    // Resources
    Route::resource('companies', CompanyController::class);
    
    // QR Code Management
    Route::prefix('qr-management')->name('qr-management.')->group(function () {
        Route::get('/', [QRManagementController::class, 'index'])->name('index');
        Route::get('/company/{company}', [QRManagementController::class, 'show'])->name('show');
        Route::get('/company/{company}/download/{branch?}', [QRManagementController::class, 'download'])->name('download');
    });
    
    // Legacy routes (keep for backward compatibility)
    Route::get('/companies/{company}/qr/{branch?}', [QRManagementController::class, 'show'])
        ->name('companies.qr');
    Route::get('/companies/{company}/download-qr/{branch?}', [QRManagementController::class, 'download'])
        ->name('companies.download-qr');
        
    Route::resource('departments', DepartmentController::class);
    Route::resource('users', UserController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('visitor-categories', VisitorCategoryController::class);

    // Security Checks
    Route::prefix('security-checks')->name('security-checks.')->group(function () {
        Route::get('/', [SecurityCheckController::class, 'index'])->name('index');
        Route::get('/create/{visitorId}', [SecurityCheckController::class, 'create'])->name('create');
        Route::post('/', [SecurityCheckController::class, 'store'])->name('store');
        Route::get('/{securityCheck}', [SecurityCheckController::class, 'show'])->name('show');
        Route::get('/{securityCheck}/print', [SecurityCheckController::class, 'print'])->name('print');
    });

});

/*
|----------------------------------------------------------------------|
| Company Panel Routes (Role: company)
|----------------------------------------------------------------------|
*/
Route::prefix('company')
    ->middleware(['auth:company', 'verified', 'role:company'])
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
        Route::post('/visitors/{id}/visit', [VisitorController::class, 'submitVisit'])->name('visitors.visit.submit');
        Route::get('/visitor-history', [VisitorController::class, 'history'])->name('visitors.history');
        Route::get('/visitor-entry', [VisitorController::class, 'entryPage'])->name('visitors.entry.page');
        Route::post('/visitor-entry-toggle/{id}', [VisitorController::class, 'toggleEntry'])->name('visitors.entry.toggle');
        Route::get('/visitors/{id}/pass', [VisitorController::class, 'printPass'])->name('visitors.pass');
        
        // Face Recognition
        Route::post('/visitors/{visitor}/verify-face', [FaceRecognitionController::class, 'verifyVisitor'])->name('visitors.verify-face');
        Route::post('/visitors/{visitor}/register-face', [FaceRecognitionController::class, 'registerVisitor'])->name('visitors.register-face');
        Route::post('/visitors/{visitor}/checkin-face', [FaceRecognitionController::class, 'checkInWithFace'])->name('visitors.checkin-face');
        Route::post('/visitors/{visitor}/checkout-face', [FaceRecognitionController::class, 'checkOutWithFace'])->name('visitors.checkout-face');

        // Approvals
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/visitors', [ReportController::class, 'visitors'])->name('visitors');
            Route::get('/visitors/export', [ReportController::class, 'exportVisitors'])->name('visitors.export');
            Route::get('/visits', [ReportController::class, 'visits'])->name('visits');
            Route::get('/visits/export', [ReportController::class, 'exportVisits'])->name('visits.export');
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
Route::prefix('api')->name('api.')->group(function () {
    // Face API
    Route::prefix('face')->name('face.')->group(function () {
        Route::post('/detect', [FaceRecognitionController::class, 'apiDetect'])->name('detect');
        Route::post('/verify', [FaceRecognitionController::class, 'apiVerify'])->name('verify');
        Route::post('/register', [FaceRecognitionController::class, 'apiRegister'])->name('register');
        Route::get('/models', [FaceRecognitionController::class, 'getModels'])->name('models');
    });

    // Visitor API
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

