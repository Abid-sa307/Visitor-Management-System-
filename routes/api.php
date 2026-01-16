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
Route::get('/companies/{company}/branches', function (\App\Models\Company $company) {
    return $company->branches()->get(['id', 'name']);
});

// Get departments for a company
Route::get('/companies/{company}/departments', function (\App\Models\Company $company) {
    return $company->departments()->get(['id', 'name']);
});

// Get departments for a branch
Route::get('/branches/{branch}/departments', function (Branch $branch) {
    return $branch->departments()->get(['id', 'name']);
});

// Get face recognition setting for a company
Route::get('/companies/{company}/face-recognition', function (\App\Models\Company $company) {
    return response()->json([
        'enabled' => (bool) $company->face_recognition_enabled
    ]);
});

// Get notification preference for a company
Route::get('/companies/{company}/notification-preference', function (\App\Models\Company $company) {
    return response()->json([
        'enable_visitor_notifications' => (bool) $company->enable_visitor_notifications,
        'company_name' => $company->name,
        'debug' => [
            'company_id' => $company->id,
            'enable_visitor_notifications_raw' => $company->enable_visitor_notifications,
            'enable_visitor_notifications_cast' => (bool) $company->enable_visitor_notifications
        ]
    ]);
});
