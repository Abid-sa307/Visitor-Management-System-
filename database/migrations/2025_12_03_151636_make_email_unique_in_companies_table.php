<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the unique constraint already exists
        $constraintExists = \DB::select("SELECT COUNT(*) as count FROM information_schema.table_constraints 
            WHERE table_name = 'companies' 
            AND constraint_type = 'UNIQUE' 
            AND constraint_schema = DATABASE()");
            
        $emailColumnUnique = false;
        
        if ($constraintExists[0]->count > 0) {
            // Check if the unique constraint is on the email column
            $emailConstraint = \DB::select("SELECT COUNT(*) as count FROM information_schema.key_column_usage 
                WHERE table_name = 'companies' 
                AND column_name = 'email' 
                AND constraint_schema = DATABASE()");
                
            $emailColumnUnique = $emailConstraint[0]->count > 0;
        }
        
        if (!$emailColumnUnique) {
            Schema::table('companies', function (Blueprint $table) {
                // Remove any duplicate emails first
                $duplicates = \DB::table('companies')
                    ->select('email', \DB::raw('COUNT(*) as count'))
                    ->groupBy('email')
                    ->having('count', '>', 1)
                    ->get();

                foreach ($duplicates as $duplicate) {
                    // Keep one record and delete the rest
                    $keep = \App\Models\Company::where('email', $duplicate->email)->first();
                    if ($keep) {
                        \App\Models\Company::where('email', $duplicate->email)
                            ->where('id', '!=', $keep->id)
                            ->delete();
                    }
                }

                // Now add the unique constraint
                $table->string('email')->unique()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });
    }
};
