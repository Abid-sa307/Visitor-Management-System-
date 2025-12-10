<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // In the generated migration file (e.g., database/migrations/2023_01_01_000000_create_visitor_categories_table.php)
public function up()
{
    Schema::create('visitor_categories', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->text('description')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_categories');
    }
};
