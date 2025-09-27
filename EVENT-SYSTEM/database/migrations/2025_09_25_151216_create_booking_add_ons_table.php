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
        Schema::create('booking_add_ons', function (Blueprint $table) {
            $table->id('bookingId')->references('bookingId')->on('bookings');// Compund key
            $table->id('addonId')->references('addonId')->on('add_ons');// Compound key referencing add_ons table
            $table->integer('quantity')->default(1);// Quantity of the add-on
            $table->text('notes')->nullable();// Additional notes, nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_add_ons');
    }
};
