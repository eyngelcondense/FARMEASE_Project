<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clients')->insert([
            'fullName' => 'Ryan Paulo Magnaye',
            'email' => 'magnaye.rp@gmail.com',
            'password' => Hash::make('password123'),
            'phoneNumber' => '0991384036',
            'address' => '123 Main St, Anytown, USA',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'fullName' => 'Juan Dela Cruz',
            'email' => 'delacruz.juan@gmail.com',
            'password' => Hash::make('password123'),
            'phoneNumber' => '09562532724',
            'address' => '123 Main St, Anytown, USA',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
