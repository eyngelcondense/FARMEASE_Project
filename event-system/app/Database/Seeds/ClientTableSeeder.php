<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'       => 'Client A',
                'email'      => 'magnaye.rp@gmail.com',
                'phone'      => '09123456789',
                'address'    => '123 Main St, City, Country',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $this->db->table('client')->insertBatch($data);
    }
}
