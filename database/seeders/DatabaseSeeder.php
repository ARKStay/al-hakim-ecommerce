<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(8)->create();

        User::factory()->create([
            'name' => 'Admin Toko Al Hakim',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'account_status' => 'active',
        ]);

        User::factory()->create([
            'name' => 'Muhammad Daffa Al Hakim',
            'username' => 'daffa',
            'email' => 'daffaspecs@gmail.com',
            'role' => 'user',
            'account_status' => 'active',
        ]);
    }
}
