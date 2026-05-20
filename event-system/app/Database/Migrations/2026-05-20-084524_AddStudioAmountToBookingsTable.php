<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStudioAmountToBookingsTable extends Migration
{
    public function up()
    {
        // Add studio_amount column to bookings table
        $this->forge->addColumn('bookings', [
            'studio_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => 'overtime_amount'
            ]
        ]);
    }

    public function down()
    {
        // Remove studio_amount column
        $this->forge->dropColumn('bookings', 'studio_amount');
    }
}
