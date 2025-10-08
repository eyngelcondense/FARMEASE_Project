<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingStaffTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'booking_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'staff_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'duty' => [
                'type'    => 'ENUM',
                'constraint' => ['Host', 'Cleaner', 'Cook', 'Guide'],
                'null'    => true,
            ]
        ]);
        $this->forge->addKey(['booking_id', 'staff_id'], true);
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('staff_id', 'staff', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('BookingStaff');
    }

    public function down()
    {
        $this->forge->dropTable('BookingStaff');
    }
}
