<?php

namespace Database\Factories;

use App\Models\Product;
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
            'name' => fake()->name,
            'sku' => strtoupper(implode('-', fake()->words(2)) . '-' . date('Y') . '-' . fake()->randomLetter . fake()->randomLetter),
            'price' => fake()->randomDigit(),
            'image' => fake()->imageUrl(),
            'currency' => fake()->randomLetter().fake()->randomLetter(),
            'quantity' => fake()->randomNumber(),
            'status' => Product::HIDDEN
        ];
    }
}
