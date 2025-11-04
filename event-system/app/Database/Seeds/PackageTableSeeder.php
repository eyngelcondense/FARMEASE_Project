<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PackageTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Standard Package',
                'description' => 'Basic amenities and services for a comfortable stay.',
                'price' => 1500.00,
            ],
            [
                'name' => 'Premium Package',
                'description' => 'Includes additional perks such as guided tours and special dining options.',
                'price' => 3000.00,
            ],
            [
                'name' => 'Deluxe Package',
                'description' => 'All-inclusive experience with luxury accommodations and exclusive activities.',
                'price' => 5000.00,
            ],
        ];

        foreach ($data as $package) {
            $this->db->table('packages')->insert($package);
        }
    }
}
