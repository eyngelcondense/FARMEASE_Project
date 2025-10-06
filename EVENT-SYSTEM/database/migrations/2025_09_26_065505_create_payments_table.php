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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('bookings_id')->constrained()->onDelete('cascade'); // Foreign key referencing bookings table
            $table->decimal('amount', 10, 2);
            $table->datetime('paymentDate'); // Date and time of payment
            $table->string('method'); // Method of payment (e.g., credit card
            $table->string('receiptNo',50)->nullable(); // Receipt or transaction details, nullable
            $table->string('remarks'); // Additional remarks, nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
