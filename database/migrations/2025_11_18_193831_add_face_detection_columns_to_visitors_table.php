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
        Schema::table('visitors', function (Blueprint $table) {
            if (!Schema::hasColumn('visitors', 'face_encoding')) {
                $table->longText('face_encoding')->nullable()->after('photo');
            }
            if (!Schema::hasColumn('visitors', 'face_image')) {
                $table->longText('face_image')->nullable()->after('face_encoding');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            if (Schema::hasColumn('visitors', 'face_encoding')) {
                $table->dropColumn('face_encoding');
            }
            if (Schema::hasColumn('visitors', 'face_image')) {
                $table->dropColumn('face_image');
            }
        });
    }
};
