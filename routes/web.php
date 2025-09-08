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
    SettingsController
};
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VisitorsExport;

/*
|----------------------------------------------------------------------|
| Public Routes
|----------------------------------------------------------------------|
*/
Route::get('/', fn() => view('welcome'));

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
| Shared (auth) routes accessible by both Superadmin and Company users
|----------------------------------------------------------------------|
*/
Route::middleware(['auth'])->group(function () {
    // AJAX: Departments for a company (used in user/visitor forms)
    Route::get('/companies/{company}/departments', [DepartmentController::class, 'getByCompany'])
        ->name('companies.departments');
});

/*
|----------------------------------------------------------------------|
| Super Admin Panel Routes
|----------------------------------------------------------------------|
*/
Route::middleware(['auth', 'verified', 'role:superadmin'])->group(function () {

    // Dashboard
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

    // Reports
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
| Company Panel Routes
|----------------------------------------------------------------------|
*/
Route::prefix('company')->middleware(['auth', 'role:company'])->name('company.')->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); // Access profile edit
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Update profile   
    
    // Visitors Routes
    Route::resource('visitors', VisitorController::class)->middleware(CheckMasterPageAccess::class . ':visitors');
    Route::post('/visitors/{id}/visit', [VisitorController::class, 'submitVisit'])->name('visitors.visit.submit');

    // History & Entry
    Route::get('/visitor-history', [VisitorController::class, 'history'])->name('visitors.history');
    Route::get('/visitor-entry', [VisitorController::class, 'entryPage'])->name('visitors.entry.page');

    // Approvals Routes
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
