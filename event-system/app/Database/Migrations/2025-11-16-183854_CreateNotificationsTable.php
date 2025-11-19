<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        // Check if table exists, if not create it
        if (!$this->db->tableExists('notifications')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255'
                ],
                'message' => [
                    'type' => 'TEXT'
                ],
                'type' => [
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'default' => 'info'
                ],
                'is_read' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true
                ],
                'related_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => true
                ],
                'related_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true
                ],
                'created_at' => [
                    'type' => 'DATETIME'
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ]
            ]);
            
            $this->forge->addKey('id', true);
            $this->forge->addKey('is_read');
            $this->forge->addKey('user_id');
            $this->forge->addKey('created_at');
            $this->forge->createTable('notifications');
        }
    }

    public function down()
    {
        $this->forge->dropTable('notifications');
    }
}