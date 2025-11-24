<?php
// app/Database/Migrations/2024_01_01_000000_create_contracts_table.php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContractsTable extends Migration
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
            'booking_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'contract_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'content' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'terms_conditions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'signature_data' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'signature_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'signed_contract_path' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'sent', 'signed', 'expired', 'cancelled'],
                'default' => 'draft',
            ],
            'sent_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('client_id', 'clients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('contracts');
    }

    public function down()
    {
        $this->forge->dropTable('contracts');
    }
}