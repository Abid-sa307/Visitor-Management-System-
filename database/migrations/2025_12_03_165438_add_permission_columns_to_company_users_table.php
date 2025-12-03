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
        Schema::table('company_users', function (Blueprint $table) {
            $table->boolean('can_access_qr_code')->default(false)->after('company_id');
            $table->boolean('can_access_visitor_category')->default(false)->after('can_access_qr_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_users', function (Blueprint $table) {
            $table->dropColumn(['can_access_qr_code', 'can_access_visitor_category']);
        });
    }
};
