<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$company = \App\Models\Company::where('name', 'LIKE', '%Basic%')->first();
if ($company) {
    echo "Found Company: " . $company->name . "\n";
    echo "Notification Enabled: " . ($company->enable_visitor_notifications ? 'YES' : 'NO') . "\n";
} else {
    echo "Company not found.\n";
}
