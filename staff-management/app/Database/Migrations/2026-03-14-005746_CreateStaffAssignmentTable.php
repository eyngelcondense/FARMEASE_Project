<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffAssignmentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'staff_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'booking_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['assigned', 'accepted', 'completed', 'cancelled'],
                'default'    => 'assigned',
                'null'       => false,
            ],
            'notes' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'assigned_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => false,
                'default'    => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => false,
                'default'    => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
                'update'     => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addKey('staff_id', false, false);
        $this->forge->addKey('booking_id', false, false);
        $this->forge->addUniqueKey(['staff_id', 'booking_id']);
        $this->forge->addForeignKey('staff_id', 'staffs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('staff_assignments', true);
    }

    public function down()
    {
        $this->forge->dropTable('staff_assignments', true);
    }
}
