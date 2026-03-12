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
            $table->foreignId('bookings_id')->constrained()->onDelete('cascade');// Compund key
            $table->foreignId('packages_id')->constrained()->onDelete('cascade');// Compound key referencing packages table
            $table->integer('quantity')->default(1);// Quantity of the package
            $table->text('note')->nullable();// Additional notes, nullable
            $table->primary(['bookings_id', 'packages_id']);
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
