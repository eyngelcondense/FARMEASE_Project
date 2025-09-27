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
        Schema::create('booking_staff', function (Blueprint $table) {
            $table->id('bookingId')->references('bookingId')->on('bookings');// Compund key
            $table->id('staffId')->references('staffId')->on('staff');// Compound
            $table->string('duty',50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_staff');
    }
};
