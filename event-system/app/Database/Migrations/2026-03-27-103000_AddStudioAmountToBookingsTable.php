<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStudioAmountToBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('bookings', [
            'studio_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => 'addons_amount'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', 'studio_amount');
    }
}
