<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDataRequestsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'registered_email' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'request_type' => [
                'type' => 'ENUM',
                'constraint' => ['booking_history', 'personal_data', 'data_correction', 'data_deletion', 'other']
            ],
            'details' => [
                'type' => 'TEXT'
            ],
            'booking_reference' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true
            ],
            'valid_id_file' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => '45'
            ],
            'user_agent' => [
                'type' => 'TEXT'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'processing', 'completed', 'rejected'],
                'default' => 'pending'
            ],
            'submitted_at' => [
                'type' => 'DATETIME'
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('email');
        $this->forge->addKey('status');
        $this->forge->createTable('data_requests');
    }

    public function down()
    {
        $this->forge->dropTable('data_requests');
    }
}