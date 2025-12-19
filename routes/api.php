<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Test route to verify API is working
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now()->toDateTimeString()
    ]);
});
