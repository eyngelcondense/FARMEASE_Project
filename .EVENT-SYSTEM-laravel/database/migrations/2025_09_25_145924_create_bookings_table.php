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
            $table->id(); // Primary key
            $table->foreignId('client_id')->constrained()->onDelete('cascade');// Foreign key to clients table
            $table->string('eventType'); // e.g., wedding, conference
            $table->date('eventDate'); 
            $table->time('startTime'); 
            $table->unsignedInteger('duration'); // hours
            $table->unsignedInteger('pax'); // attendees
            $table->string('status'); 
            $table->timestamps();
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
