<?php

use Illuminate\Support\Facades\Route;
use App\Models\Company;

Route::get('/debug-company-settings', function() {
    echo "<h2>Company Notification Settings</h2>";
    
    // Get all companies
    $companies = Company::all(['id', 'name', 'enable_visitor_notifications']);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Visitor Notifications</th></tr>";
    
    foreach ($companies as $company) {
        echo "<tr>";
        echo "<td>{$company->id}</td>";
        echo "<td>{$company->name}</td>";
        echo "<td style='text-align: center; color: " . ($company->enable_visitor_notifications ? 'green' : 'red') . "; font-weight: bold;'>";
        echo $company->enable_visitor_notifications ? '✅ YES' : '❌ NO';
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<h3>Looking for 'basic' company:</h3>";
    $basicCompany = Company::where('name', 'like', '%basic%')->first();
    if ($basicCompany) {
        echo "<p><strong>Found:</strong> {$basicCompany->name} (ID: {$basicCompany->id}) - Notifications: " . ($basicCompany->enable_visitor_notifications ? 'YES' : 'NO') . "</p>";
    } else {
        echo "<p>No company with 'basic' in name found.</p>";
    }
    
    echo "<h3>Enable Notifications for Basic Company:</h3>";
    echo "<form method='post' action='/debug-enable-notifications'>";
    echo csrf_field();
    echo "<input type='number' name='company_id' placeholder='Company ID' required>";
    echo "<button type='submit'>Enable Notifications</button>";
    echo "</form>";
    
    if (request()->has('enabled')) {
        echo "<p style='color: green; font-weight: bold;'>✅ Notifications enabled for company ID: " . request('company_id') . "</p>";
    }
});

Route::post('/debug-enable-notifications', function() {
    $companyId = request('company_id');
    $company = Company::find($companyId);
    
    if ($company) {
        $company->enable_visitor_notifications = true;
        $company->save();
        
        return redirect('/debug-company-settings')->with('enabled', true)->with('company_id', $companyId);
    } else {
        echo "<p style='color: red;'>Company not found!</p>";
    }
});
