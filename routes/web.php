<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SecurityCheckController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
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

// Dynamic Dropdown Routes (Guard Agnostic)
Route::prefix('api')->name('api.')->middleware('web')->group(function () {
    Route::get('/companies/{company}/departments', [DepartmentController::class, 'getByCompany'])->name('departments.by_company');
    Route::get('/companies/{company}/branches', [BranchController::class, 'getByCompany'])->name('branches.by_company');
    Route::get('/branches/{branch}/departments', [BranchController::class, 'getDepartments'])->name('departments.by_branch');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Companies
    Route::resource('companies', CompanyController::class);
    
    // Branches
    Route::resource('branches', BranchController::class);
    
    // Departments
    Route::resource('departments', DepartmentController::class); // Ensure this resource route exists
    
    // Visitors
    Route::resource('visitors', VisitorController::class);
    Route::get('visitors/{visitor}/pass', [VisitorController::class, 'showPass'])->name('visitors.pass');
    Route::get('visitors/{visitor}/pass-pdf', [VisitorController::class, 'downloadPassPdf'])->name('visitors.pass.pdf');
    Route::post('visitors/{visitor}/archive', [VisitorController::class, 'archive'])->name('visitors.archive');
    Route::post('visitors/{visitor}/checkin', [VisitorController::class, 'checkIn'])->name('visitors.checkin');
    Route::post('visitors/{visitor}/checkout', [VisitorController::class, 'checkOut'])->name('visitors.checkout');

    // Visitor Categories
    Route::resource('visitor-categories', \App\Http\Controllers\VisitorCategoryController::class);

    // Employees
    Route::resource('employees', EmployeeController::class);

    // QR Codes
    Route::get('/qr-scan', [QrCodeController::class, 'scan'])->name('qr.scan');
    Route::post('/qr-process', [QrCodeController::class, 'process'])->name('qr.process');

    // Security Checks
    Route::resource('security-checks', SecurityCheckController::class);
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Admin Users
    Route::resource('users', UserController::class);
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
