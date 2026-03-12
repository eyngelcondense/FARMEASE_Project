<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVenuesTable extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'capacity' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false
            ],
            'base_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00
            ],
            'image_url' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active'
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

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('venues');
    }

    public function down()
    {
        $this->forge->dropTable('venues');
    }
}