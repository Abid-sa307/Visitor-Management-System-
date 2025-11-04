<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_users', function (Blueprint $table) {
            if (!Schema::hasColumn('company_users', 'master_pages')) {
                $table->json('master_pages')->nullable()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_users', function (Blueprint $table) {
            if (Schema::hasColumn('company_users', 'master_pages')) {
                $table->dropColumn('master_pages');
            }
        });
    }
};
