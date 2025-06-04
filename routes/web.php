<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
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
    $latestVisitors = Visitor::latest()->take(6)->get();
    return view('dashboard', compact('latestVisitors'));
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
    Route::resource('users', UserController::class);
});

// Auth scaffolding routes
require __DIR__.'/auth.php';
