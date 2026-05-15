<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffAvailabilityTable extends Migration
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
            'date' => [
                'type'       => 'DATE',
                'null'       => false,
            ],
            'start_time' => [
                'type'       => 'TIME',
                'null'       => true,
            ],
            'end_time' => [
                'type'       => 'TIME',
                'null'       => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['available', 'unavailable', 'leave'],
                'default'    => 'available',
                'null'       => false,
            ],
            'notes' => [
                'type'       => 'TEXT',
                'null'       => true,
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

        $this->forge->addKey('id', false, true);
        $this->forge->addKey('staff_id', false, false);
        $this->forge->addUniqueKey(['staff_id', 'date']);
        $this->forge->addForeignKey('staff_id', 'staffs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('staff_availability');
    }

    public function down()
    {
        $this->forge->dropTable('staff_availability');
    }
}
