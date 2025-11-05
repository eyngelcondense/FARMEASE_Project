<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackagesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('packages')->insert(
            [
                'packageName' => 'Basic Package',
                'packageDescription' => 'This is a basic package suitable for small events.',
                'basePrice' => 199.99,
                'packageDuration' => 4,
                'overtimePrice' => 49.99
            ]
        );
    }
}
