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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->foreignId('visitor_category_id')->nullable()->constrained();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('photo')->nullable();
            $table->foreignId('department_id')->nullable()->constrained();
            $table->string('purpose')->nullable();
            $table->string('person_to_visit')->nullable();
            $table->json('documents')->nullable(); // store doc list
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
