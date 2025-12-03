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
        // This is a no-op migration since we've already handled the unique constraint
        // in the previous migration. We're keeping this as a placeholder to maintain
        // migration order consistency.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't drop the unique key in the down method to be safe
        // You can manually drop it if needed
    }
};
