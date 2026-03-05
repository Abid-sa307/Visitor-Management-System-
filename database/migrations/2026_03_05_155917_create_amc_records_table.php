<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amc_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('package_name')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_mode')->nullable(); // Cash, Bank Transfer, Cheque, UPI, etc.
            $table->string('transaction_reference')->nullable();
            $table->enum('status', ['active', 'expired', 'upcoming'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amc_records');
    }
};
