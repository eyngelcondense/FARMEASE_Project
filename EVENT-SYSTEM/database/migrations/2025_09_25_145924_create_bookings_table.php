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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('bookigId'); // Primary key
            $table->id('clientId')->references('clientId')->on('clients'); // Foreign key referencing clients table
            $table->string('eventType'); // Type of event (e.g., wedding, birthday)
            $table->date('eventDate'); // Date of the event
            $table->timestamp('startTime'); //time start
            $table->int('duration'); // Duration in hours
            $table->int('pax'); // Number of guests
            $table->string('stauts');
            $table->timestamp('createdAt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
