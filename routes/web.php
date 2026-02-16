<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\EmployeeController;
// use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SecurityCheckController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Auth\CompanyLoginController;
use App\Http\Controllers\SecurityQuestionController;
use App\Http\Controllers\QRManagementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SitemapController;
use App\Models\Company;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Public Static Pages
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::view('/pricing', 'pricing')->name('pricing');
Route::view('/partner', 'partner')->name('partner');
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy-policy');
Route::view('/terms-of-use', 'pages.terms-of-use')->name('terms-of-use');
Route::view('/refund-and-cancellation', 'pages.refund-and-cancellation')->name('refund-and-cancellation');
Route::view('/service-agreement', 'pages.service-agreement')->name('service-agreement');

// SEO Pages
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/robots.txt', function() {
    return response("User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml') . "\nHost: https://visitormanagmentsoftware.com")->header('Content-Type', 'text/plain');
});

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Auth Routes (Custom)
Route::get('/company/login', [CompanyLoginController::class, 'showLoginForm'])->name('company.login');

// Solutions / Industries
Route::view('/industrial-manufacturing-unit', 'pages.industrial-manufacturing-unit')->name('industrial-manufacturing-unit');
Route::view('/industrial-and-cold-storage', 'pages.industrial-and-cold-storage')->name('industrial-and-cold-storage');
Route::view('/school-and-colleges', 'pages.school-and-colleges')->name('school-and-colleges');
Route::view('/resident-societies', 'pages.resident-societies')->name('resident-societies');
Route::view('/resident-buildings', 'pages.resident-buildings')->name('resident-buildings');
Route::view('/office-workplace-management', 'pages.office-workplace-management')->name('office-workplace-management');
Route::view('/healthcare-facilities', 'pages.healthcare-facilities')->name('healthcare-facilities');
Route::view('/malls-and-events', 'pages.malls-and-events')->name('malls-and-events');
Route::view('/temple-and-dargah', 'pages.temple-and-dargah')->name('temple-and-dargah');

// Public QR Code Routes (no auth required) - MUST be before any middleware groups
Route::get('/companies/{company}/public/qr', [QRManagementController::class, 'show'])->name('companies.public.qr');
Route::get('/companies/{company}/public-qr', [QRManagementController::class, 'show'])->name('companies.public-qr'); // Alias with hyphen

// Public QR Scan Route (for visitors to fill information)
Route::get('/qr/scan/{company}/{branch?}', [QRManagementController::class, 'scan'])->name('qr.scan');

// Public Visitor Registration Routes (for visitors to register themselves)
Route::get('/qr/visitor/create/{company}', [QRManagementController::class, 'createVisitor'])->name('qr.visitor.create');
Route::get('/qr/visitor/create/{company}/{branch}', [QRManagementController::class, 'createVisitor'])->name('qr.visitor.create.branch');
Route::post('/qr/visitor/store/{company}', [QRManagementController::class, 'storeVisitor'])->name('qr.visitor.store');
Route::post('/qr/visitor/store/{company}/{branch}', [QRManagementController::class, 'storeVisitor'])->name('qr.visitor.store.branch');

// Public Visitor Mark In/Out Route (for visitors to mark themselves in/out)
Route::post('/visitors/{visitor}/toggle-entry', [VisitorController::class, 'toggleEntry'])->name('visitors.entry.toggle');

// Public Visitor Pass Routes (for visitors to print/download their passes)
Route::get('/public/visitors/{visitor}/pass', [VisitorController::class, 'showPass'])->name('public.visitors.pass');
Route::get('/public/visitors/{visitor}/pass/pdf', [VisitorController::class, 'showPassPdf'])->name('public.visitors.pass.pdf');


// Dynamic Dropdown Routes (Guard Agnostic)
Route::prefix('api')->name('api.')->middleware('web')->group(function () {
    Route::get('/companies/{company}/departments', [DepartmentController::class, 'getByCompany'])->name('departments.by_company');
    Route::get('/companies/{company}/branches', [BranchController::class, 'getByCompany'])->name('branches.by_company');
    Route::get('/branches/{branch}/departments', [BranchController::class, 'getDepartments'])->name('departments.by_branch');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Notifications
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Visitor Management Extra Routes
    Route::get('visitors/history', [VisitorController::class, 'history'])->name('visitors.history');
    Route::get('visitors/visits', [VisitorController::class, 'visitsIndex'])->name('visits.index');
    Route::get('visitors/inout', [VisitorController::class, 'entryPage'])->name('visitors.entry.page');
    Route::get('visitors/approvals', [VisitorController::class, 'approvals'])->name('visitors.approvals');
    Route::patch('visitors/{visitor}/approve', [VisitorController::class, 'approve'])->name('approvals.approve');
    Route::patch('visitors/{visitor}/reject', [VisitorController::class, 'reject'])->name('approvals.reject');
    Route::get('visitors/{visitor}/visit', [VisitorController::class, 'visitForm'])->name('visitors.visit.form');
    Route::post('visitors/{visitor}/visit', [VisitorController::class, 'submitVisit'])->name('visitors.visit.submit');
    Route::post('visitors/{visitor}/undo', [VisitorController::class, 'undoVisit'])->name('visitors.visit.undo');

    // Resources (Standard)
    Route::resource('companies', CompanyController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('visitors', VisitorController::class)->except(['show']);
    Route::resource('visitor-categories', \App\Http\Controllers\VisitorCategoryController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('users', UserController::class);
    
    // Specific Action Routes
    Route::get('visitors/{visitor}/pass', [VisitorController::class, 'showPass'])->name('visitors.pass');
    Route::get('visitors/{visitor}/pass-pdf', [VisitorController::class, 'downloadPassPdf'])->name('visitors.pass.pdf');
    Route::post('visitors/{visitor}/archive', [VisitorController::class, 'archive'])->name('visitors.archive');
    Route::post('visitors/{visitor}/checkin', [VisitorController::class, 'checkIn'])->name('visitors.checkin');
    Route::post('visitors/{visitor}/checkout', [VisitorController::class, 'checkOut'])->name('visitors.checkout');

    // QR Management (Auth Required)
    Route::prefix('qr')->name('qr.')->group(function() {
        Route::get('/management', [QRManagementController::class, 'index'])->name('index');
        Route::get('/companies/{company}/qr', [QRManagementController::class, 'show'])->name('show');
        Route::get('/companies/{company}/qr/download', [QRManagementController::class, 'download'])->name('download');
    });
    
    // Legacy route alias for qr-management.index
    Route::get('/qr-management', [QRManagementController::class, 'index'])->name('qr-management.index');
    
    // Public visitor routes (outside auth middleware)
});


// Public routes (no auth required)
Route::prefix('public')->name('public.')->group(function() {
    Route::get('/companies/{company}/visitors/{visitor}', [QRManagementController::class, 'publicVisitorIndex'])->name('visitor.show');
    
    // Legacy-style routes as requested
    Route::get('/companies/{company}/visitors/{visitor}/visit', [QRManagementController::class, 'showVisitForm'])->name('visitor.visit.form');
    Route::get('/companies/{company}/branches/{branch}/visitors/{visitor}/visit', [QRManagementController::class, 'showVisitFormWithBranch'])->name('visitor.visit.form.branch');
    
    Route::post('/companies/{company}/visitors/{visitor}/visit', [QRManagementController::class, 'storeVisit'])->name('visitor.visit.store');
    Route::post('/companies/{company}/branches/{branch}/visitors/{visitor}/visit', [QRManagementController::class, 'storeVisitWithBranch'])->name('visitor.visit.store.branch');

    Route::post('/company/{company}/visitor/{visitor}/visit/undo', [VisitorController::class, 'undoVisit'])->name('visitor.visit.undo');
    Route::get('/visitors/{visitor}/pass', [VisitorController::class, 'showPass'])->name('visitors.pass');
    Route::get('/visitors/{visitor}/pass-pdf', [VisitorController::class, 'downloadPassPdf'])->name('visitors.pass.pdf');
    
    // Public QR Code for Branch (Path-based)
    Route::get('/companies/{company}/branches/{branch}', [QRManagementController::class, 'show'])->name('companies.branches.show');
});

Route::middleware('auth')->group(function() {
    // QR & Security
    // Route::get('/qr-scan', [QrCodeController::class, 'scan'])->name('qr.scan');
    // Route::post('/qr-process', [QrCodeController::class, 'process'])->name('qr.process');
    
    Route::prefix('security-checks')->name('security-checks.')->group(function() {
        Route::get('/', [SecurityCheckController::class, 'index'])->name('index');
        Route::get('/{visitorId}/create', [SecurityCheckController::class, 'create'])->name('create');
        Route::get('/{visitorId}/checkout', [SecurityCheckController::class, 'createCheckout'])->name('create-checkout');
        Route::post('/{visitorId}/toggle', [SecurityCheckController::class, 'toggleSecurity'])->name('toggle');
        Route::get('/{id}/print', [SecurityCheckController::class, 'print'])->name('print');
    });
    Route::resource('security-checks', SecurityCheckController::class)->except(['index', 'create']);
    
    Route::get('security-questions/create/checkin', [SecurityQuestionController::class, 'createCheckin'])->name('security-questions.create.checkin');
    Route::get('security-questions/create/checkout', [SecurityQuestionController::class, 'createCheckout'])->name('security-questions.create.checkout');
    Route::resource('security-questions', SecurityQuestionController::class);
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function() {
        Route::get('/', [ReportController::class, 'visitors'])->name('index');
        Route::get('/visitors', [ReportController::class, 'visitors'])->name('visitors');
        Route::get('/visits', [ReportController::class, 'visits'])->name('visits');
        Route::get('/security', [ReportController::class, 'securityChecks'])->name('security');
        Route::get('/approval', [ReportController::class, 'approvals'])->name('approval');
        Route::get('/hourly', [ReportController::class, 'hourlyReport'])->name('hourly');
        
        // Final exports matching view names
        Route::get('/visitors/export', [ReportController::class, 'exportVisitors'])->name('visitors.export');
        Route::get('/visits/export', [ReportController::class, 'exportVisits'])->name('visits.export');
        Route::get('/security/export', [ReportController::class, 'exportSecurityChecks'])->name('security.export');
        Route::get('/approval/export', [ReportController::class, 'exportApprovals'])->name('approval.export');
        Route::get('/hourly/export', [ReportController::class, 'exportHourlyReport'])->name('hourly.export');
    });

    // --- COMPANY ALIASES (to support old route names in views) ---
    Route::prefix('company')->name('company.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        
        Route::resource('users', UserController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('branches', BranchController::class);
        Route::resource('employees', EmployeeController::class);
        Route::resource('visitor-categories', \App\Http\Controllers\VisitorCategoryController::class);
        
        // Aliased extras for visitors (MOVE ABOVE RESOURCE if not using separate prefix, 
        // but here we have GET visitors/{visitor} from resource so static ones must be first)
        Route::get('visitors/history', [VisitorController::class, 'history'])->name('visitors.history');
        Route::get('visitors/visits', [VisitorController::class, 'visitsIndex'])->name('visits.index');
        Route::get('visitors/approvals', [VisitorController::class, 'approvals'])->name('approvals.index');
        Route::get('visitors/entry-page', [VisitorController::class, 'entryPage'])->name('visitors.entry.page');
        Route::post('visitors/{visitor}/toggle-entry', [VisitorController::class, 'toggleEntry'])->name('visitors.entry.toggle');
        
        Route::resource('visitors', VisitorController::class)->except(['show']);
        
        Route::prefix('security-checks')->name('security-checks.')->group(function() {
            Route::get('/', [SecurityCheckController::class, 'index'])->name('index');
            Route::get('/{visitorId}/create', [SecurityCheckController::class, 'create'])->name('create');
            Route::get('/{visitorId}/checkout', [SecurityCheckController::class, 'createCheckout'])->name('create-checkout');
            Route::post('/{visitorId}/toggle', [SecurityCheckController::class, 'toggleSecurity'])->name('toggle');
            Route::get('/{id}/print', [SecurityCheckController::class, 'print'])->name('print');
        });
        Route::resource('security-checks', SecurityCheckController::class)->except(['index', 'create']);

        Route::get('security-questions/create/checkin', [SecurityQuestionController::class, 'createCheckin'])->name('security-questions.create.checkin');
        Route::get('security-questions/create/checkout', [SecurityQuestionController::class, 'createCheckout'])->name('security-questions.create.checkout');
        Route::resource('security-questions', SecurityQuestionController::class);

        // More Visitor extras
        Route::patch('visitors/{visitor}/approve', [VisitorController::class, 'approve'])->name('approvals.approve');
        Route::patch('visitors/{visitor}/reject', [VisitorController::class, 'reject'])->name('approvals.reject');
        Route::get('visitors/{visitor}/visit', [VisitorController::class, 'visitForm'])->name('visitors.visit.form');
        Route::post('visitors/{visitor}/visit', [VisitorController::class, 'submitVisit'])->name('visitors.visit.submit');
        Route::post('visitors/{visitor}/undo', [VisitorController::class, 'undoVisit'])->name('visitors.visit.undo');
        Route::get('visitors/{visitor}/pass', [VisitorController::class, 'showPass'])->name('visitors.pass');
        
        Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
        
        // Reports (if specifically named in sidebar)
        Route::prefix('reports')->name('reports.')->group(function() {
            Route::get('/visitors', [ReportController::class, 'visitors'])->name('visitors');
            Route::get('/visits', [ReportController::class, 'visits'])->name('visits');
            Route::get('/security', [ReportController::class, 'securityChecks'])->name('security');
            Route::get('/approval', [ReportController::class, 'approvals'])->name('approval');
            Route::get('/hourly', [ReportController::class, 'hourlyReport'])->name('hourly');
            
            // Company Exports
            Route::get('/visitors/export', [ReportController::class, 'exportVisitors'])->name('visitors.export');
            Route::get('/visits/export', [ReportController::class, 'exportVisits'])->name('visits.export');
            Route::get('/security/export', [ReportController::class, 'exportSecurityChecks'])->name('security.export');
            Route::get('/approval/export', [ReportController::class, 'exportApprovals'])->name('approval.export');
            Route::get('/hourly/export', [ReportController::class, 'exportHourlyReport'])->name('hourly.export');
        });
    });
});

require __DIR__.'/auth.php';

// Debug routes (Keep at bottom)
Route::get('/debug-company-settings', function() {
    echo "<h2>Company Notification Settings</h2>";
    $companies = \App\Models\Company::all(['id', 'name', 'enable_visitor_notifications']);
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'><tr><th>ID</th><th>Name</th><th>Visitor Notifications</th></tr>";
    foreach ($companies as $company) {
        echo "<tr><td>{$company->id}</td><td>{$company->name}</td><td style='text-align: center; color: " . ($company->enable_visitor_notifications ? 'green' : 'red') . "; font-weight: bold;'>" . ($company->enable_visitor_notifications ? '✅ YES' : '❌ NO') . "</td></tr>";
    }
    echo "</table>";
});