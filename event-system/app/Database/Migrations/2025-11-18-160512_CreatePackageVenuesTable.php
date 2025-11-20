<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePackagesVenues extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'package_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'venue_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'is_primary' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
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
        $this->forge->addForeignKey('package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('venue_id', 'venues', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('packages_venues');
    }

    public function down()
    {
        $this->forge->dropTable('packages_venues');
    }
}