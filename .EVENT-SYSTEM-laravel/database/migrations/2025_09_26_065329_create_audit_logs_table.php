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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('logId'); // Primary key
            $table->foreignId('admin_id')->constrained('admin')->onDelete('cascade'); // Foreign key referencing users table
            $table->string('action'); // Action performed (e.g., create, update, delte)
            $table->string('tableName'); // Name of the table where the action was performed
            $table->integer('recordId'); // ID of the record affected
            $table->text('oldValue'); // Old value before the action
            $table->text('newValue'); // New value after the action
            $table->timestamp('timestamp'); // Timestamp of when the action was performed
            $table->string('ipAddress'); // IP address of the user who performed the action
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
