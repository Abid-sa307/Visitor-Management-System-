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
        Schema::table('company_users', function (Blueprint $table) {
            if (!Schema::hasColumn('company_users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
            if (!Schema::hasColumn('company_users', 'remember_token')) {
                $table->rememberToken();
            }
            
            // Check if the unique constraint already exists
            $constraintExists = \DB::select("SELECT COUNT(*) as count FROM information_schema.table_constraints 
                WHERE table_name = 'company_users' 
                AND constraint_name = 'company_users_email_unique' 
                AND constraint_schema = DATABASE()");            
            
            if ($constraintExists[0]->count === 0) {
                // Only add the unique constraint if it doesn't exist
                $table->string('email')->unique()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to rollback these changes
    }
};
