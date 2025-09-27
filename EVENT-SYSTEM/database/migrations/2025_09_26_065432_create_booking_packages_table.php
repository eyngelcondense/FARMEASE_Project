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
        Schema::create('booking_packages', function (Blueprint $table) {
            $table->id('bookingId')->references('bookingId')->on('bookings');// Compund key
            $table->id('packageId')->references('packageId')->on('packages');// Compound key referencing packages table
            $table->integer('quantity')->default(1);// Quantity of the package
            $table->text('note')->nullable();// Additional notes, nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_packages');
    }
};
