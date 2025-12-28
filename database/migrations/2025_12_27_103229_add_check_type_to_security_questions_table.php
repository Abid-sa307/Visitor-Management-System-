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
        Schema::table('security_questions', function (Blueprint $table) {
            $table->enum('check_type', ['checkin', 'checkout', 'both'])->default('both')->after('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security_questions', function (Blueprint $table) {
            $table->dropColumn('check_type');
        });
    }
};
