<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->timestamp('in_time')->nullable();
            $table->timestamp('out_time')->nullable();
            $table->string('status')->default('Pending');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            //
        });
    }
};
