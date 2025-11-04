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
        Schema::create('admin', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('username'); // Full name of the admin
            $table->string('password');//hashed password
            $table->string('role'); // Role of the admin (e.g., superadmin, editor)
            $table->integer('linkedId'); // ID linking to other tables if necessary
            $table->timestamp('createdAt'); // Creation timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
