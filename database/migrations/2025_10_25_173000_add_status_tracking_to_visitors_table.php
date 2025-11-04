<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            if (!Schema::hasColumn('visitors', 'last_status')) {
                $table->string('last_status')->nullable()->after('status');
            }
            if (!Schema::hasColumn('visitors', 'status_changed_at')) {
                $table->timestamp('status_changed_at')->nullable()->after('last_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            if (Schema::hasColumn('visitors', 'status_changed_at')) {
                $table->dropColumn('status_changed_at');
            }
            if (Schema::hasColumn('visitors', 'last_status')) {
                $table->dropColumn('last_status');
            }
        });
    }
};
