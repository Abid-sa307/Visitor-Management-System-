<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixDuplicateVisitorCategories extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Check if the table exists and has data
        if (Schema::hasTable('visitor_categories')) {
            // Drop foreign key constraints from visitors table
            Schema::table('visitors', function (Blueprint $table) {
                if (Schema::hasColumn('visitors', 'visitor_category_id')) {
                    $table->dropForeign(['visitor_category_id']);
                }
            });
            
            // Drop the existing table
            Schema::dropIfExists('visitor_categories');
        }

        // Recreate the table with the correct structure
        Schema::create('visitor_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Re-add the foreign key constraint
        Schema::table('visitors', function (Blueprint $table) {
            $table->foreign('visitor_category_id')
                  ->references('id')
                  ->on('visitor_categories')
                  ->onDelete('set null');
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        // This migration is not reversible
    }
}