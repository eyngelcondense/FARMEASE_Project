<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAddonTable extends Migration
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
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('addons');
    }

    public function down()
    {
        $this->forge->dropTable('addons');
    }
}
