<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRefundProofFieldsToBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('bookings', [
            'refund_reference_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'refund_processed_at',
            ],
            'refund_screenshot_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'refund_reference_number',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', [
            'refund_reference_number',
            'refund_screenshot_path',
        ]);
    }
}