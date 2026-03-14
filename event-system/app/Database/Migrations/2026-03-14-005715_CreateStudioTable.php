<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudioTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'capacity' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('studios');
    }

    public function down()
    {
        $this->forge->dropTable('studios');
    }
}
