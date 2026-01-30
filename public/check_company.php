<?php
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Company;

echo "=== Company Notification Settings Check ===\n\n";

// Get all companies
$companies = Company::all(['id', 'name', 'enable_visitor_notifications']);

foreach ($companies as $company) {
    echo "Company ID: {$company->id}\n";
    echo "Name: {$company->name}\n";
    echo "Visitor Notifications Enabled: " . ($company->enable_visitor_notifications ? 'YES' : 'NO') . "\n";
    echo "----------------------------------------\n";
}

echo "\n=== Looking for 'basic' company ===\n";
$basicCompany = Company::where('name', 'like', '%basic%')->first();
if ($basicCompany) {
    echo "Found Basic Company:\n";
    echo "ID: {$basicCompany->id}\n";
    echo "Name: {$basicCompany->name}\n";
    echo "Visitor Notifications Enabled: " . ($basicCompany->enable_visitor_notifications ? 'YES' : 'NO') . "\n";
} else {
    echo "No company with 'basic' in name found.\n";
}

echo "\n=== First Company ===\n";
$firstCompany = Company::first();
if ($firstCompany) {
    echo "First Company:\n";
    echo "ID: {$firstCompany->id}\n";
    echo "Name: {$firstCompany->name}\n";
    echo "Visitor Notifications Enabled: " . ($firstCompany->enable_visitor_notifications ? 'YES' : 'NO') . "\n";
}
?>
