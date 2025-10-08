<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
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
            'admin_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'table_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'record_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'old_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'new_value' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'timestamp' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => null,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('admin_id', 'admin', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('AuditLogs', false, [
            'fields' => [
                'timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->droptable('AuditLogs');
    }
}
