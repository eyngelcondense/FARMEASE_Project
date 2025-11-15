<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateClientTable extends Migration
{
    public function up()
    {

        $fields = [
            'profile_pic' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'user_id'
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {

        $this->forge->dropColumn('clients', ['profile_pic', 'phone']);
    }
}
