<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudioImagesTable extends Migration
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
            'studio_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'image_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => false,
            ],
            'image_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'alt_text' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_primary' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('studio_id');
        $this->forge->addKey(['studio_id', 'is_primary']);
        $this->forge->addKey(['studio_id', 'status']);
        $this->forge->addKey(['studio_id', 'sort_order']);

        $this->forge->addForeignKey('studio_id', 'studios', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('studio_images');
    }

    public function down()
    {
        $this->forge->dropTable('studio_images');
    }
}
