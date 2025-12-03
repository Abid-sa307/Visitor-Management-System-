<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use Illuminate\Http\Request;

Route::get('/test/create-company-user', function () {
    // Find the existing company
    $company = Company::where('email', 'nntvms@gmail.com')->first();
    
    if (!$company) {
        return 'No company found with email: nntvms@gmail.com';
    }
    
    // Reset the password
    $newPassword = 'password123';
    $company->password = Hash::make($newPassword);
    $company->save();
    
    $message = '✅ Password has been reset for: ' . $company->email . '<br>';
    $message .= 'New password: ' . $newPassword . '<br><br>';
    
    // Try to authenticate
    $credentials = [
        'email' => $company->email,
        'password' => $newPassword
    ];
    
    // Debug: Show the credentials being used
    $message .= 'Attempting login with:<br>';
    $message .= 'Email: ' . $credentials['email'] . '<br>';
    $message .= 'Password: ' . $credentials['password'] . '<br><br>';
    
    if (Auth::guard('company')->attempt($credentials)) {
        $message .= '✅ Login successful! You should be redirected to the dashboard.';
        return redirect()->route('dashboard');
    } else {
        $message .= '❌ Login failed. Possible issues:<br>';
        $message .= '1. Check if the password was hashed correctly in the database<br>';
        $message .= '2. Verify the authentication guard is properly configured<br>';
        $message .= '3. Check the Company model implements Authenticatable<br><br>';
        
        // Check password in database
        $dbPassword = DB::table('companies')->where('email', $company->email)->value('password');
        $message .= 'Database password: ' . (strlen($dbPassword) > 10 ? 'Hashed (' . substr($dbPassword, 0, 10) . '...)' : 'Invalid') . '<br>';
        
        // Check if password matches
        $message .= 'Password matches: ' . (Hash::check($newPassword, $dbPassword) ? '✅' : '❌') . '<br>';
    }
    
    return $message;
});

// Create a test company user
Route::get('/test/create-company-user-account', function () {
    // Check if company exists
    $company = \App\Models\Company::where('email', 'nntvms@gmail.com')->first();
    
    if (!$company) {
        return 'Company not found. Please create a company first.';
    }
    
    // Create a company user account
    $companyUser = \App\Models\CompanyUser::updateOrCreate(
        ['email' => 'company@example.com'],
        [
            'name' => 'Test Company User',
            'password' => 'password123',
            'company_id' => $company->id,
            'role' => 'company',
            'master_pages' => []
        ]
    );
    
    return [
        'status' => 'success',
        'message' => 'Company user created successfully',
        'user' => [
            'id' => $companyUser->id,
            'name' => $companyUser->name,
            'email' => $companyUser->email,
            'company_id' => $companyUser->company_id
        ],
        'login_url' => url('/company/login')
    ];
});

// Test company login with debug info
Route::get('/test/company-login', function () {
    // Clear any existing auth
    auth('company')->logout();
    
    // Test credentials
    $email = 'nntvms@gmail.com';
    $password = 'password123';
    
    // Get the company
    $company = \App\Models\Company::where('email', $email)->first();
    
    if (!$company) {
        return [
            'status' => 'error',
            'message' => 'Company not found',
            'email' => $email
        ];
    }
    
    // Manually hash the password to ensure consistency
    $hashedPassword = bcrypt($password);
    
    // Update the password directly in the database
    \DB::table('companies')
        ->where('id', $company->id)
        ->update(['password' => $hashedPassword]);
    
    // Refresh the company model
    $company->refresh();
    
    // Try to login using the guard
    $credentials = [
        'email' => $email,
        'password' => $password
    ];
    
    // Debug info
    $debug = [
        'company_id' => $company->id,
        'password_in_db' => $company->password,
        'password_matches' => \Illuminate\Support\Facades\Hash::check($password, $company->password),
        'auth_attempt' => auth('company')->attempt($credentials),
    ];
    
    // If login failed, try to find out why
    if (!$debug['auth_attempt']) {
        // Check if the user exists with these credentials
        $user = \App\Models\Company::where('email', $email)->first();
        
        if (!$user) {
            $debug['error'] = 'No user found with this email';
        } else if (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            $debug['error'] = 'Password does not match';
            $debug['password_hash'] = $user->password;
            $debug['input_hash'] = bcrypt($password);
        } else {
            $debug['error'] = 'Unknown authentication error';
            
            // Try to manually log in the user
            if (auth('company')->loginUsingId($user->id)) {
                $debug['manual_login'] = 'Success';
                $debug['authenticated_user'] = auth('company')->user();
            } else {
                $debug['manual_login'] = 'Failed';
            }
        }
    }
    
    return [
        'status' => $debug['auth_attempt'] ? 'success' : 'error',
        'message' => $debug['auth_attempt'] ? 'Login successful!' : 'Login failed',
        'debug' => $debug
    ];
});

// Test login with updated password hashing
Route::get('/test/company-login-old', function () {
    // Clear any existing auth
    auth('company')->logout();
    
    // Test credentials
    $email = 'nntvms@gmail.com';
    $password = 'password123';
    
    // Get the company
    $company = \App\Models\Company::where('email', $email)->first();
    
    if (!$company) {
        return [
            'status' => 'error',
            'message' => 'Company not found',
            'email' => $email
        ];
    }
    
    // Update the password (will be hashed by the mutator)
    $company->password = $password;
    $company->save();
    
    // Try to login
    $credentials = [
        'email' => $email,
        'password' => $password
    ];
    
    if (auth('company')->attempt($credentials)) {
        $user = auth('company')->user();
        return [
            'status' => 'success',
            'message' => 'Login successful!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ];
    } else {
        return [
            'status' => 'error',
            'message' => 'Login failed',
            'debug' => [
                'company_id' => $company->id,
                'password_in_db' => $company->password,
                'password_matches' => \Illuminate\Support\Facades\Hash::check($password, $company->password),
                'auth_attempt' => auth('company')->attempt($credentials),
            ]
        ];
    }
});

// Reset password and test login
Route::get('/test/reset-company-password', function () {
    // Find the company
    $company = \App\Models\Company::where('email', 'nntvms@gmail.com')->first();
    
    if (!$company) {
        return 'Company not found';
    }
    
    // Set a known password
    $password = 'password123';
    $company->password = \Illuminate\Support\Facades\Hash::make($password);
    $company->save();
    
    // Try to login
    $credentials = [
        'email' => 'nntvms@gmail.com',
        'password' => $password
    ];
    
    // Clear any existing auth
    auth('company')->logout();
    
    // Try to authenticate
    if (auth('company')->attempt($credentials)) {
        $user = auth('company')->user();
        return [
            'status' => 'success',
            'message' => 'Login successful!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ];
    } else {
        return [
            'status' => 'error',
            'message' => 'Login failed',
            'debug' => [
                'company_exists' => (bool)$company,
                'password_matches' => \Illuminate\Support\Facades\Hash::check($password, $company->password),
                'auth_attempt' => auth('company')->attempt($credentials),
            ]
        ];
    }
});

// Direct login test
Route::get('/test/direct-login', function (Request $request) {
    $email = 'nntvms@gmail.com';
    $password = 'password123';
    
    // Get the company
    $company = Company::where('email', $email)->first();
    
    if (!$company) {
        return 'Company not found';
    }
    
    // Manually check password
    $passwordMatches = Hash::check($password, $company->password);
    
    // Try to login with guard
    $loginAttempt = Auth::guard('company')->attempt([
        'email' => $email,
        'password' => $password
    ]);
    
    // Try to login with web guard (for testing)
    $webLoginAttempt = Auth::guard('web')->attempt([
        'email' => $email,
        'password' => $password
    ]);
    
    // Get the authenticated user
    $user = Auth::guard('company')->user();
    
    // Get the current guard
    $currentGuard = Auth::getDefaultDriver();
    
    return view('test-login', [
        'company' => $company,
        'passwordMatches' => $passwordMatches,
        'loginAttempt' => $loginAttempt,
        'webLoginAttempt' => $webLoginAttempt,
        'user' => $user,
        'currentGuard' => $currentGuard,
        'password' => $password,
        'hashedPassword' => $company->password,
    ]);
});
