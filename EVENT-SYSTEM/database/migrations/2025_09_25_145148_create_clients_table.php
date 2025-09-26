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
            $table->id('clientID'); // Primary key
            $table->string('fullName'); // Full name of the client
            $table->string('email')->unique(); // Unique email address
            $table->string('password'); // This is Hashed password
            $table->string('phoneNumber')->nullable(); // Phone number, nullable
            $table->string('address')->nullable(); // Address, nullable
            $table->timestamp('createdAt')->useCurrent(); // Creation timestamp

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
