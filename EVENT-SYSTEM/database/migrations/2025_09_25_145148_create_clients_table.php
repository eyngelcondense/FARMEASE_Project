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
       Schema::create('clients', function (Blueprint $table) {
            $table->id();// Primary key
            $table->string('fullName');// Full name of the client
            $table->string('email')->unique();// Unique email address
            $table->string('password');// Hashed password
            $table->string('phoneNumber')->unique();// Phone number (optional)
            $table->string('address')->nullable();// Address (optional)
            $table->timestamps();// Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
