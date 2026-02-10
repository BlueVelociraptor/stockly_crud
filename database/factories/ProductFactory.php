<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Product_Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->words(5, true),
            "price" => fake()->randomFloat(2, 5),
            "description" => fake()->sentence(),
        ];
    }

    public function withProductImage()
    {
        return $this->has(Product_Image::factory()->state([
            "public_url" => "http://example.com",
            "public_id" => "123",
        ]));
    }
}
