<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserFieldsToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'username', // optional
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
                'after'      => 'role', // optional
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['role', 'password']);
    }
}
