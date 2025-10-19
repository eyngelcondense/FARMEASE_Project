<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingAddonTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'booking_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'addon_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ]
        ]);
        $this->forge->addKey(['booking_id', 'addon_id'], true);
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('addon_id', 'addons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('booking_addons');

    }

    public function down()
    {
        $this->forge->dropTable('booking_addons');
    }
}
