<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sizes;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat 10 pengguna acak
        User::factory(8)->create();

        // Membuat satu pengguna spesifik
        User::factory()->create([
            'name' => 'ADMIN',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Daffa',
            'username' => 'daffa',
            'email' => 'daffa@gmail.com',
            'role' => 'user',
        ]);

        $this->call([CategorySeeder::class, SizesSeeder::class]);
        Product::factory(10)->recycle([
            Category::all(),
            Sizes::all()
        ])->create();
    }
}
