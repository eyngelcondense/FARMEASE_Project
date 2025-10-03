<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('add_ons')->insert([
            'addonName' => 'Extra Chairs',
            'description' => 'Additional chairs for guests',
            'price' => 150.00,
            'admin_id' => 1, // Assuming admin with ID 1 exists
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
