<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateClientTable extends Migration
{
    public function up()
    {
        // Add new fields
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
        // Remove the added fields (rollback)
        $this->forge->dropColumn('clients', ['profile_pic', 'phone']);
    }
}
