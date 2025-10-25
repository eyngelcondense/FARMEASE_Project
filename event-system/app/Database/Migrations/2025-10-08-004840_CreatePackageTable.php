<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePackageTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'base_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
            ],
            'overtime_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('packages');
    }

    public function down()
    {
        $this->forge->dropTable('packages');
    }
}
