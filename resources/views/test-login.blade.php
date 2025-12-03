<!DOCTYPE html>
<html>
<head>
    <title>Login Test</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Login Test Results</h1>
    
    <h2>Company Details</h2>
    <p><strong>ID:</strong> {{ $company->id ?? 'N/A' }}</p>
    <p><strong>Name:</strong> {{ $company->name ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $company->email ?? 'N/A' }}</p>
    <p><strong>Password Matches:</strong> 
        @if($passwordMatches)
            <span class="success">✅ Yes</span>
        @else
            <span class="error">❌ No</span>
        @endif
    </p>
    
    <h2>Login Attempts</h2>
    <p><strong>Company Guard Login:</strong> 
        @if($loginAttempt)
            <span class="success">✅ Success</span>
        @else
            <span class="error">❌ Failed</span>
        @endif
    </p>
    
    <p><strong>Web Guard Login:</strong> 
        @if($webLoginAttempt)
            <span class="success">✅ Success</span>
        @else
            <span class="error">❌ Failed</span>
        @endif
    </p>
    
    <h2>Current Authentication</h2>
    <p><strong>Current Guard:</strong> {{ $currentGuard }}</p>
    <p><strong>Authenticated User:</strong> {{ $user ? $user->email : 'Not authenticated' }}</p>
    
    <h2>Debug Info</h2>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p><strong>Hashed Password:</strong> {{ $hashedPassword }}</p>
    
    <h2>Next Steps</h2>
    <ol>
        <li>If password doesn't match, the password in the database doesn't match what we're trying.</li>
        <li>If company guard login fails but web guard works, there's an issue with the company guard configuration.</li>
        <li>If both logins fail, check the Company model and authentication configuration.</li>
    </ol>
    
    <h2>Raw Data</h2>
    <pre>{{ print_r([
        'company' => $company->toArray(),
        'passwordMatches' => $passwordMatches,
        'loginAttempt' => $loginAttempt,
        'webLoginAttempt' => $webLoginAttempt,
        'user' => $user ? $user->toArray() : null,
        'currentGuard' => $currentGuard,
    ], true) }}</pre>
</body>
</html>
