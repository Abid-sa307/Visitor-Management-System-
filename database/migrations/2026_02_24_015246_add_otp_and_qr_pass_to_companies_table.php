<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('otp_mark_in_out')->default(false)->after('mark_in_out_in_qr_flow')
                  ->comment('When enabled, mark-in/out requires OTP verification');
            $table->boolean('qr_visitor_pass_scan')->default(false)->after('otp_mark_in_out')
                  ->comment('When enabled, visitors can scan their visitor pass QR code for entry/exit');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['otp_mark_in_out', 'qr_visitor_pass_scan']);
        });
    }
};
