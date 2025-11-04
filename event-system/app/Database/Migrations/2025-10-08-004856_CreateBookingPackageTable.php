<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingPackageTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'booking_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'package_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'note' => [
                'type'    => 'VARCHAR',
                'constraint' => 255,
                'null'    => true]
        ]);
        $this->forge->addKey(['booking_id', 'package_id'], true);
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('booking_packages');

    }

    public function down()
    {
        $this->forge->dropTable('booking_packages');
    }
}
