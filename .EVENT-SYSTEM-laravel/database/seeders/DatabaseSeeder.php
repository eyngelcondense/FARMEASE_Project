<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(ClientTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(PackagesTableSeeder::class);
        $this->call(AddonsTableSeeder::class);
    }
}
