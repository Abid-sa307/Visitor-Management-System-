<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_users', function (Blueprint $table) {
            if (!Schema::hasColumn('company_users', 'role')) {
                $table->string('role', 50)->default('company');
            }
            if (!Schema::hasColumn('company_users', 'remember_token')) {
                $table->string('remember_token', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_users', function (Blueprint $table) {
            if (Schema::hasColumn('company_users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('company_users', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
        });
    }
};
