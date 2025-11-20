<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'booking_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false
            ],
            'event_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'event_date' => [
                'type' => 'DATE',
                'null' => false
            ],
            'start_time' => [
                'type' => 'TIME',
                'null' => false
            ],
            'end_time' => [
                'type' => 'TIME',
                'null' => false
            ],
            'total_hours' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => false
            ],
            'total_guests' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false
            ],
            'package_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'venue_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'base_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00
            ],
            'addons_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00
            ],
            'overtime_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00
            ],
            'special_requests' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'approved', 'rejected', 'cancelled', 'completed'],
                'default' => 'pending'
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'partial', 'paid', 'refunded'],
                'default' => 'pending'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('client_id', 'clients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('package_id', 'packages', 'id');
        $this->forge->addForeignKey('venue_id', 'venues', 'id');
        $this->forge->addUniqueKey('booking_reference');
        $this->forge->addKey('event_date');
        $this->forge->addKey('status');
        $this->forge->addKey(['client_id', 'event_date']);
        $this->forge->addKey(['package_id', 'venue_id']);
        $this->forge->createTable('bookings');
    }

    public function down()
    {
        $this->forge->dropTable('bookings');
    }
}