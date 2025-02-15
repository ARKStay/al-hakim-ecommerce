<?php

namespace Database\Seeders;

use App\Models\Sizes;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sizes::create([
            'name' => 'S',
        ]);

        Sizes::create([
            'name' => 'M',
        ]);

        Sizes::create([
            'name' => 'L',
        ]);

        Sizes::create([
            'name' => 'XL',
        ]);
    }
}
