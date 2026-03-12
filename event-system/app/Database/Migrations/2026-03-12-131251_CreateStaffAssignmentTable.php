<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffAssignmentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'staff_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'booking_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('staff_id');
        $this->forge->addKey('booking_id');
        $this->forge->addForeignKey('staff_id', 'staffs', 'id');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id');
        $this->forge->createTable('staff_assignments');
    }

    public function down()
    {
        $this->forge->dropTable('staff_assignments');
    }
}
