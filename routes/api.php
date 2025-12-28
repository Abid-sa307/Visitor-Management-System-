<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Company;
use App\Models\Branch;

// Test route to verify API is working
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Get branches for a company
Route::get('/companies/{company}/branches', function (Company $company) {
    $branches = $company->branches()->get(['id', 'name']);
    return $branches->pluck('name', 'id');
});

// Get departments for a branch
Route::get('/branches/{branch}/departments', function (Branch $branch) {
    return $branch->departments()
        ->select('id', 'name')
        ->orderBy('name')
        ->get();
});

// Get face recognition setting for a company
Route::get('/companies/{company}/face-recognition', function (Company $company) {
    return response()->json([
        'enabled' => (bool) $company->face_recognition_enabled
    ]);
});
