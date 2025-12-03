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
    Schema::table('companies', function (Blueprint $table) {
        $table->string('password')->nullable();
        $table->rememberToken();
        $table->timestamp('email_verified_at')->nullable();
    });
}

public function down()
{
    Schema::table('companies', function (Blueprint $table) {
        $table->dropColumn(['password', 'remember_token', 'email_verified_at']);
    });
}
};
