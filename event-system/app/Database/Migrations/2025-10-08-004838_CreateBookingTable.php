<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingTable extends Migration
{
    public function up()
    {
        $this->forge->addfield([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'event_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'event_date' => [
                'type' => 'DATE'
            ],
            'start_time' => [
                'type' => 'TIME'
            ],
            'duration_hours' => [
                'type' => 'INT',
                'constraint' => 2
            ],
            'pax' => [
                'type' => 'INT',
                'constraint' => 5
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'Pending'
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
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('booking');
        $this->forge->AddForeignKey('client_id', 'client', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('booking');
    }
}
