<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, make the branch_id nullable
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->change();
        });
        
        // Then migrate existing branch relationships to the pivot table
        if (Schema::hasTable('branch_user')) {
            $users = DB::table('users')->whereNotNull('branch_id')->get();
            
            foreach ($users as $user) {
                DB::table('branch_user')->insertOrIgnore([
                    'branch_id' => $user->branch_id,
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the first branch relationship before making the column required
        if (Schema::hasTable('branch_user')) {
            $branchUsers = DB::table('branch_user')
                ->select('user_id', 'branch_id')
                ->whereIn('user_id', function($query) {
                    $query->select('id')->from('users');
                })
                ->get()
                ->groupBy('user_id');
            
            foreach ($branchUsers as $userId => $branches) {
                if ($branch = $branches->first()) {
                    DB::table('users')
                        ->where('id', $userId)
                        ->update(['branch_id' => $branch->branch_id]);
                }
            }
        }
        
        // Make the branch_id required again
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable(false)->change();
        });
    }
};
