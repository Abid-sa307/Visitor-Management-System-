<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $c = App\Models\Company::where('name', 'Basic')->first();
    echo "Company ID: " . ($c->id ?? 'Not Found') . "\n";
    
    if ($c) {
        $b = App\Models\Branch::where('company_id', $c->id)->where('name', 'like', '%basic one%')->first();
        echo "Branch ID: " . ($b->id ?? 'Not Found') . "\n";
        
        if ($b) {
            $depts = App\Models\Department::where('branch_id', $b->id)->get();
            echo "Direct Branch Departments: " . $depts->count() . "\n";
            foreach($depts as $d) {
                echo " - " . $d->name . " (ID: " . $d->id . ")\n";
            }
        }
        
        $all = App\Models\Department::where('company_id', $c->id)->get();
        echo "All Company Departments: " . $all->count() . "\n";
        foreach($all as $d) {
            echo " - " . $d->name . " (BranchID: " . ($d->branch_id ?? 'NULL') . ")\n";
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
