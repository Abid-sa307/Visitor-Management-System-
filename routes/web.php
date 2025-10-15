<?php

use Illuminate\Support\Facades\Route;
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
    Auth\CompanyLoginController,
    Auth\CompanyAuthController,
    ApprovalController,
    SettingsController,
    BlogController,
};
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VisitorsExport;
use App\Http\Middleware\CheckMasterPageAccess;



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

Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{slug}', [BlogController::class, 'show']);


/*
|----------------------------------------------------------------------|
| Company Auth Routes (login/logout)
|----------------------------------------------------------------------|
*/
Route::get('/company/login', [CompanyLoginController::class, 'showLoginForm'])->name('company.login');
Route::post('/company/login', [CompanyLoginController::class, 'login'])->name('company.login.custom');
Route::post('/company/logout', [CompanyAuthController::class, 'logout'])->name('company.logout');

/*
|----------------------------------------------------------------------|
| Authenticated Routes (Shared for both Superadmin and Company)
|----------------------------------------------------------------------|
*/
Route::middleware(['auth'])->group(function () {
    // AJAX: Get departments for a company (used in user/visitor forms)
    Route::get('/companies/{company}/departments', [DepartmentController::class, 'getByCompany'])
        ->name('companies.departments');
});

/*
|----------------------------------------------------------------------|
| Super Admin Panel Routes (Role: superadmin)
|----------------------------------------------------------------------|
*/
Route::middleware(['auth', 'verified', 'role:superadmin'])->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Visitors Routes
    Route::resource('visitors', VisitorController::class);
    Route::get('/visitor-history', [VisitorController::class, 'history'])->name('visitors.history');
    Route::get('/visitor-entry', [VisitorController::class, 'entryPage'])->name('visitors.entry.page');
    Route::post('/visitor-entry-toggle/{id}', [VisitorController::class, 'toggleEntry'])->name('visitors.entry.toggle');
    Route::get('/visitors/{id}/pass', [VisitorController::class, 'printPass'])->name('visitors.pass');
    Route::get('/visitors/{id}/visit', [VisitorController::class, 'visitForm'])->name('visitors.visit.form');
    Route::post('/visitors/{id}/visit', [VisitorController::class, 'submitVisit'])->name('visitors.visit.submit');

    // Visitor Approvals
    Route::get('/visitor-approvals', [VisitorController::class, 'approvals'])->name('visitors.approvals');

    // Manage Companies, Users, Departments, Employees
    Route::resource('companies', CompanyController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('users', UserController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('visitor-categories', VisitorCategoryController::class);

    // Security Check Routes
    Route::prefix('security-checks')->group(function () {
        Route::get('/', [SecurityCheckController::class, 'index'])->name('security-checks.index');
        Route::get('/create/{visitorId}', [SecurityCheckController::class, 'create'])->name('security-checks.create');
        Route::post('/', [SecurityCheckController::class, 'store'])->name('security-checks.store');
    });

    // Reports Routes
    Route::prefix('reports')->group(function () {
        Route::get('/visitors', [VisitorController::class, 'report'])->name('visitors.report');
        Route::get('/visitors/inout', [VisitorController::class, 'inOutReport'])->name('visitors.report.inout');
        Route::get('/visitors/approvals', [VisitorController::class, 'approvalReport'])->name('visitors.report.approval');
        Route::get('/visitors/security', [VisitorController::class, 'securityReport'])->name('visitors.report.security');
        Route::get('/visitors/export', fn() => Excel::download(new VisitorsExport, 'visitors_report.xlsx'))->name('visitors.report.export');
    });
});

/*
|----------------------------------------------------------------------|
| Company Panel Routes (Role: company)
|----------------------------------------------------------------------|
*/
Route::prefix('company')->middleware(['auth', 'role:company'])->name('company.')->group(function () {
    // Dashboard Route for Company
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes for Company users
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Visitors Routes for Company
    Route::resource('visitors', VisitorController::class)->middleware(CheckMasterPageAccess::class . ':visitors');
    Route::post('/visitors/{id}/visit', [VisitorController::class, 'submitVisit'])->name('visitors.visit.submit');

    // History & Entry Routes for Company
    Route::get('/visitor-history', [VisitorController::class, 'history'])->name('visitors.history');
    Route::get('/visitor-entry', [VisitorController::class, 'entryPage'])->name('visitors.entry.page');

    // Approvals Routes for Company
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');

    // Reports (Company Panel)
    Route::prefix('reports')->group(function () {
        Route::get('/visitors', [VisitorController::class, 'report'])->name('visitors.report');
        Route::get('/visitors/inout', [VisitorController::class, 'inOutReport'])->name('visitors.report.inout');
        Route::get('/visitors/approvals', [VisitorController::class, 'approvalReport'])->name('visitors.report.approval');
        Route::get('/visitors/security', [VisitorController::class, 'securityReport'])->name('visitors.report.security');
        Route::get('/visitors/export', fn() => Excel::download(new VisitorsExport, 'visitors_report.xlsx'))->name('visitors.report.export');
    });

    // Employees & Departments Routes
    Route::resource('employees', EmployeeController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('departments', DepartmentController::class);
    Route::resource('users', UserController::class);

    // Security Check Routes for Company
    Route::resource('security-checks', SecurityCheckController::class)->only(['index', 'create', 'store']);
});

// Breeze/Auth Routes (handled by Laravel)
require __DIR__ . '/auth.php';


