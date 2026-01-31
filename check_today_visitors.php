<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Checking Today's Visitor Data ===\n\n";

$currentDate = now()->format('Y-m-d');
echo "Current Date: $currentDate\n\n";

// Check if there are any visitors with in_time today
$todayVisitors = DB::table('visitors')
    ->whereNotNull('in_time')
    ->whereDate('in_time', '=', $currentDate)
    ->count();

echo "Total visitors with in_time today: $todayVisitors\n\n";

// Show some sample records if any exist
if ($todayVisitors > 0) {
    $sampleVisitors = DB::table('visitors')
        ->whereNotNull('in_time')
        ->whereDate('in_time', '=', $currentDate)
        ->select('name', 'in_time', 'branch_id', 'company_id')
        ->limit(5)
        ->get();
    
    echo "Sample visitor records for today:\n";
    foreach ($sampleVisitors as $visitor) {
        echo "- {$visitor->name} | In: {$visitor->in_time} | Branch: {$visitor->branch_id} | Company: {$visitor->company_id}\n";
    }
} else {
    echo "No visitors found with in_time for today.\n\n";
    
    // Check if there are any recent visitors at all
    $recentVisitors = DB::table('visitors')
        ->whereNotNull('in_time')
        ->orderBy('in_time', 'desc')
        ->limit(5)
        ->select('name', 'in_time', 'branch_id', 'company_id')
        ->get();
    
    echo "Most recent visitor records:\n";
    foreach ($recentVisitors as $visitor) {
        echo "- {$visitor->name} | In: {$visitor->in_time} | Branch: {$visitor->branch_id} | Company: {$visitor->company_id}\n";
    }
    
    echo "\nTo test the hourly report, you need to have visitors check in today.\n";
    echo "Try checking in a test visitor and then refresh the hourly report.\n";
}

echo "\n=== Debug Complete ===\n";
