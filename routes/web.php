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
use App\Http\Middleware\CheckMasterPageAccess;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VisitorsExport;

// ----------------- Public -----------------
Route::get('/', fn() => view('welcome'));

// ----------------- Company Auth Routes -----------------
Route::get('/company/login', [CompanyLoginController::class, 'showLoginForm'])->name('company.login');
Route::post('/company/login', [CompanyLoginController::class, 'login'])->name('company.login.custom');
Route::post('/company/logout', [CompanyAuthController::class, 'logout'])->name('company.logout');

// ----------------- Super Admin Dashboard -----------------
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:superadmin'])
    ->name('dashboard');

// ----------------- Super Admin Protected Routes -----------------
Route::middleware(['auth', 'role:superadmin'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Visitors
    Route::resource('visitors', VisitorController::class);
    Route::get('/visitor-history', [VisitorController::class, 'history'])->name('visitors.history');
    Route::get('/visitor-entry', [VisitorController::class, 'entryPage'])->name('visitors.entry.page');
    Route::post('/visitor-entry-toggle/{id}', [VisitorController::class, 'toggleEntry'])->name('visitors.entry.toggle');
    Route::get('/visitors/{id}/pass', [VisitorController::class, 'printPass'])->name('visitors.pass');
    Route::get('/visitor-approvals', [VisitorController::class, 'approvals'])->name('visitors.approvals');
    Route::get('/visitors/{id}/visit', [VisitorController::class, 'visitForm'])->name('visitors.visit.form');
    Route::post('/visitors/{id}/visit', [VisitorController::class, 'submitVisit'])->name('visitors.visit.submit');

    // Companies & Departments
    Route::resource('companies', CompanyController::class);
    Route::resource('departments', DepartmentController::class);
    Route::get('/companies/{company}/departments', [DepartmentController::class, 'getByCompany'])
        ->name('companies.departments');

    // Users, Employees, Visitor Categories
    Route::resource('users', UserController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('visitor-categories', VisitorCategoryController::class);

    // Security Checks
    Route::prefix('security-checks')->group(function () {
        Route::get('/', [SecurityCheckController::class, 'index'])->name('security-checks.index');
        Route::get('/create/{visitorId}', [SecurityCheckController::class, 'create'])->name('security-checks.create');
        Route::post('/', [SecurityCheckController::class, 'store'])->name('security-checks.store');
    });

    // Visitor Check-up
    Route::get('/visitors/{id}/checkup', [VisitorController::class, 'checkupForm'])->name('visitors.checkup');
    Route::post('/visitors/{id}/checkup', [VisitorController::class, 'submitCheckup'])->name('visitors.checkup.submit');

    // Reports
    Route::get('/reports/visitors', [VisitorController::class, 'report'])->name('visitors.report');
    Route::get('/reports/visitors/export', fn() => Excel::download(new VisitorsExport, 'visitors_report.xlsx'))
        ->name('visitors.report.export');

    // Optional Profile View Page
    Route::get('/profile-view', fn() => view('layouts.profile.profile'))->name('profile.view');
});

// ----------------- Company Panel Protected Routes -----------------
Route::prefix('company')->middleware(['auth', 'role:company'])->name('company.')->group(function () {

    Route::middleware(CheckMasterPageAccess::class . ':dashboard')
        ->get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Visitors
    Route::middleware(CheckMasterPageAccess::class . ':visitors')
        ->resource('visitors', VisitorController::class);

    // Route::middleware(CheckMasterPageAccess::class. 'visi')

    Route::middleware(CheckMasterPageAccess::class . ':visitors')
        ->post('/visitors/{id}/visit', [VisitorController::class, 'submitVisit'])
        ->name('visitors.visit.submit');

    Route::middleware(CheckMasterPageAccess::class . ':visitor_history')
        ->get('/visitor-history', [VisitorController::class, 'history'])
        ->name('visitors.history');

    Route::middleware(CheckMasterPageAccess::class . ':visitor_inout')
        ->get('/visitor-entry', [VisitorController::class, 'entryPage'])
        ->name('visitors.entry.page');

    Route::middleware(CheckMasterPageAccess::class . ':approvals')
        ->get('/approvals', [ApprovalController::class, 'index'])
        ->name('approvals.index');

    Route::middleware(CheckMasterPageAccess::class . ':reports')
        ->get('/reports', [VisitorController::class, 'report'])
        ->name('visitors.report');

    Route::get('/visitors/report/export', [VisitorController::class, 'export'])
        ->name('visitors.report.export');

    // Employees
    Route::middleware(CheckMasterPageAccess::class . ':employees')
        ->resource('employees', EmployeeController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // Visitor Categories
    Route::middleware(CheckMasterPageAccess::class . ':visitor_categories')
        ->resource('visitor-categories', VisitorCategoryController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // Departments
    Route::middleware(CheckMasterPageAccess::class . ':departments')
        ->resource('departments', DepartmentController::class);

    // Users
    Route::middleware(CheckMasterPageAccess::class . ':users')
        ->resource('users', UserController::class);

    // Security Checks
    Route::middleware(CheckMasterPageAccess::class . ':security_checks')
        ->resource('security-checks', SecurityCheckController::class)->only(['index', 'create', 'store']);

    // Visitor Checkup
    Route::middleware(CheckMasterPageAccess::class . ':visitor_checkup')
        ->get('/visitors/{id}/checkup', [VisitorController::class, 'checkupForm'])
        ->name('visitors.checkup');
});

// ----------------- Laravel Breeze Auth Routes -----------------
require __DIR__ . '/auth.php';
