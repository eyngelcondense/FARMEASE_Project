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
        Schema::create('packages', function (Blueprint $table) {
            $table->id('packageId');// Primary key
            $table->string('packageName', 100);// Package name
            $table->text('packageDescription')->nullable();// Package description
            $table->decimal('basePrice', 8, 2);// Package price
            $table->integer('packageDuration')->comment('Duration in days');// Package duration in days
            $table->decimal('overtimePrice',10,2);// created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
