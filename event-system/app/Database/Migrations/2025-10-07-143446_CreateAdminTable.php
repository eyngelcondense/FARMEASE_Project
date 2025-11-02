<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'position' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'permission' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ]
        ]);
        $this->forge->addKey('id', true); // Primary key
        $this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
        $this->forge->createTable('admins'); // Table name
    }

    public function down()
    {
        $this->forge->dropTable('admins');
    }
}
