<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use DateTimeImmutable;

class CoffeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('coffees')->insert([
            [
                'name' => 'Gold Coffee',
                'profit_margin' => 0.25,
                'created_at' => new DateTimeImmutable(),
            ],
            [
                'name' => 'Arabic Coffee',
                'profit_margin' => 0.15,
                'created_at' => new DateTimeImmutable(),
            ]
        ]);
    }
}
