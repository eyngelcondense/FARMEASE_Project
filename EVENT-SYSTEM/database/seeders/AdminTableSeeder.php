<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admin')->insert([
            'username' => 'superadmin',
            'password' => Hash::make('password123'), // It's important to hash passwords
            'role' => 'superadmin',
            'linkedId' => 0, // Assuming 0 means no linked ID
            'createdAt' => now()
        ]);
    }
}
