<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCancellationRefundFieldsToBookingsTable extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE bookings MODIFY status ENUM('pending', 'confirmed', 'approved', 'rejected', 'cancelled', 'completed', 'expired') NOT NULL DEFAULT 'pending'");

        $this->forge->addColumn('bookings', [
            'cancellation_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'special_requests',
            ],
            'cancelled_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'cancellation_reason',
            ],
            'refund_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => 'cancelled_at',
            ],
            'refund_status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'refund_amount',
            ],
            'refund_processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'refund_status',
            ],
            'no_show' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'refund_processed_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', [
            'cancellation_reason',
            'cancelled_at',
            'refund_amount',
            'refund_status',
            'refund_processed_at',
            'no_show',
        ]);

        $this->db->query("ALTER TABLE bookings MODIFY status ENUM('pending', 'confirmed', 'approved', 'rejected', 'cancelled', 'completed') NOT NULL DEFAULT 'pending'");
    }
}