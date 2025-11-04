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
            $table->foreignId('bookings_id')->constrained()->onDelete('cascade');
            $table->foreignId('add_ons_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->primary(['bookings_id', 'add_ons_id']);
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
