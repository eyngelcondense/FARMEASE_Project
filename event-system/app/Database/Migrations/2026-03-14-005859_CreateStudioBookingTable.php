<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudioBookingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'studio_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'booking_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('studio_id');
        $this->forge->addKey('booking_id');
        $this->forge->addForeignKey('studio_id', 'studios', 'id');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id');
        $this->forge->createTable('studio_bookings');
    }

    public function down()
    {
        $this->forge->dropTable('studio_bookings');
    }
}
