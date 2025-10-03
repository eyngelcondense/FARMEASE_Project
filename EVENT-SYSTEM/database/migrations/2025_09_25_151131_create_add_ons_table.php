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
        Schema::create('add_ons', function (Blueprint $table) {
            $table->id();// Primary key
            $table->string('addonName', 100);// Add-on name
            $table->text('description')->nullable();// Add-on description
            $table->decimal('price', 10, 2);// Add-on price
            $table->foreignId('admin_id')->constrained('admin')->onDelete('cascade');// Foreign key to admins table
            $table->timestamps();// Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_ons');
    }
};
