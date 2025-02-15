<?php

namespace Database\Factories;

use App\Models\Sizes;
use App\Models\Category;
use Illuminate\Support\Str;
use Database\Seeders\CategorySeeder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),  // Nama produk dengan tiga kata acak
            'slug' => Str::slug($this->faker->sentence()),  // Slug otomatis dari nama
            'description' => $this->faker->paragraph(),  // Deskripsi dengan kalimat acak
            'color' => $this->faker->words(3, true),
            'price' => $this->faker->randomFloat(2, 10, 1000),  // Harga dengan 2 desimal, rentang 10-1000
            'stock' => $this->faker->numberBetween(0, 100),  // Jumlah stok antara 0-100
            'category_id' => Category::factory(),
            'sizes_id' => Sizes::factory()
        ];
    }
}
