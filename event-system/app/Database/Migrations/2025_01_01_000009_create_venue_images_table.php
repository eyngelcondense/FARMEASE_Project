<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVenueImagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'venue_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'image_path' => [
                'type' => 'TEXT',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('venue_id');
        $this->forge->addForeignKey('venue_id', 'venues', 'id');
        $this->forge->createTable('venue_images');
    }

    public function down()
    {
        $this->forge->dropTable('venue_images');
    }
}
