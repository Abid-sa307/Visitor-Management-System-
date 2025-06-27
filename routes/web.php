<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\VisitorCategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Exports\VisitorsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Visitor;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public homepage
Route::get('/', function () {
    return view('welcome');
});

// Dashboard Route (moved to controller)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Protected Routes (only for logged-in users)
Route::middleware(['auth'])->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Companies
    Route::resource('companies', CompanyController::class);

    // Departments
    Route::resource('departments', DepartmentController::class);

    // Employees
    Route::resource('employees', EmployeeController::class);

    // Users
    Route::resource('users', UserController::class);

    // Visitor Categories
    Route::resource('visitor-categories', VisitorCategoryController::class);

    // Visitors
    Route::resource('visitors', VisitorController::class);
    Route::get('/visitor-history', [VisitorController::class, 'history'])->name('visitors.history');
    Route::get('/visitor-entry', [VisitorController::class, 'entryPage'])->name('visitors.entry.page');
    Route::post('/visitor-entry-toggle/{id}', [VisitorController::class, 'toggleEntry'])->name('visitors.entry.toggle');
    Route::get('/visitors/{id}/pass', [VisitorController::class, 'printPass'])->name('visitors.pass');
    Route::get('/visitor-approvals', [VisitorController::class, 'approvals'])->name('visitors.approvals');

    // Security Check-up
    Route::get('/visitors/{id}/checkup', [VisitorController::class, 'checkupForm'])->name('visitors.checkup');
    Route::post('/visitors/{id}/checkup', [VisitorController::class, 'submitCheckup'])->name('visitors.checkup.submit');

    // Visitor Reports + Export
    Route::get('/reports/visitors', [VisitorController::class, 'report'])->name('visitors.report');
    Route::get('/reports/visitors/export', function () {
        return Excel::download(new VisitorsExport, 'visitors_report.xlsx');
    })->name('visitors.report.export');

    // Optional duplicate profile view route (if using custom blade)
    Route::get('/profile-view', function () {
        return view('layouts.profile.profile');
    })->name('profile.view');
});

// Auth scaffolding (login, register etc.)
require __DIR__.'/auth.php';
