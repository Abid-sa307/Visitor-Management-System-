<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\VisitorCategoryController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\DB;
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

// Dashboard (with recent visitors passed to view)
Route::get('/dashboard', function () {
    $latestVisitors = \App\Models\Visitor::latest()->take(6)->get();

    $monthlyCounts = DB::table('visitors')
        ->selectRaw("DATE_FORMAT(created_at, '%b') as month, COUNT(*) as total")
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->orderByRaw("STR_TO_DATE(month, '%b')")
        ->get()
        ->pluck('total', 'month');

    return view('dashboard', [
        'latestVisitors' => $latestVisitors,
        'chartLabels' => $monthlyCounts->keys(),
        'chartData' => $monthlyCounts->values(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');



// Protected Routes (only for logged-in users)
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resourceful Routes
    Route::resource('companies', CompanyController::class);

    Route::resource('visitors', VisitorController::class);
    Route::get('/visitor-history', [VisitorController::class, 'history'])->name('visitors.history');
    Route::get('/visitor-entry', [VisitorController::class, 'entryPage'])->name('visitors.entry.page');
    Route::post('/visitor-entry-toggle/{id}', [VisitorController::class, 'toggleEntry'])->name('visitors.entry.toggle');
    Route::post('/visitors/entry/{id}/toggle', [VisitorController::class, 'toggleEntry'])->name('visitors.entry.toggle');
    Route::get('/visitors/{id}/pass', [VisitorController::class, 'printPass'])->name('visitors.pass');

    Route::resource('users', UserController::class);

    Route::resource('departments', DepartmentController::class);

    Route::resource('visitor-categories', VisitorCategoryController::class);
    
    Route::resource('employees', EmployeeController::class);



});

// Auth scaffolding routes
require __DIR__.'/auth.php';
