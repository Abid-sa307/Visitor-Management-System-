<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('visitors', function (Blueprint $table) {
            if (!Schema::hasColumn('visitors', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('status_changed_at');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('visitors', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
        });
    }

    public function down()
    {
        Schema::table('visitors', function (Blueprint $table) {
            if (Schema::hasColumn('visitors', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('visitors', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
        });
    }
};