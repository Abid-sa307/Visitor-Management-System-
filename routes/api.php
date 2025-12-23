<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Company;

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

// Get face recognition setting for a company
Route::get('/companies/{company}/face-recognition', function (Company $company) {
    return response()->json([
        'enabled' => (bool) $company->enable_face_recognition
    ]);
});
