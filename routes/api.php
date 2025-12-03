<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Branch;
use App\Models\Department;

// Add CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Public API routes (no auth for now)
Route::middleware('api')->group(function () {
    // Get branches by company
    Route::get('/branches', function (Request $request) {
        try {
            $request->validate([
                'company_id' => 'required|exists:companies,id',
            ]);

            $branches = Branch::where('company_id', $request->company_id)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json($branches);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Get departments by company
    Route::get('/departments', function (Request $request) {
        try {
            $request->validate([
                'company_id' => 'required|exists:companies,id',
            ]);

            $departments = Department::where('company_id', $request->company_id)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json($departments);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Add a test route to verify API is working
    Route::get('/test', function () {
        return response()->json([
            'success' => true,
            'message' => 'API is working!',
            'timestamp' => now()->toDateTimeString()
        ]);
    });
});
