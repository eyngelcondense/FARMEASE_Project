<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddonTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Catering Service',
                'description' => 'Delicious meals and snacks for your event.',
                'price' => 500.00,
            ],
            [
                'name' => 'Audio-Visual Equipment',
                'description' => 'High-quality sound systems and projectors for presentations and entertainment.',
                'price' => 300.00,
            ],
            [
                'name' => 'Decoration Service',
                'description' => 'Beautiful decorations to enhance the ambiance of your event.',
                'price' => 400.00,
            ],
        ];

        foreach ($data as $addon) {
            $this->db->table('addons')->insert($addon);
        }
    }
}
