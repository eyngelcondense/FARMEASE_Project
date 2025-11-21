<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingAddonsTable extends Migration
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
            'booking_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'addon_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 1
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false
            ],
            'total_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('addon_id', 'addons', 'id');
        $this->forge->addUniqueKey(['booking_id', 'addon_id']);
        $this->forge->createTable('booking_addons');
    }

    public function down()
    {
        $this->forge->dropTable('booking_addons');
    }
}