<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'booking_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ],
            'payment_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['gcash', 'paymaya', 'bank_transfer', 'cash'],
                'null' => false
            ],
            'payment_date' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'receipt_image' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'verified', 'rejected'],
                'default' => 'pending'
            ],
            'verified_by' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('verified_by', 'admins', 'id');
        $this->forge->addUniqueKey('payment_reference');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments');
    }
}