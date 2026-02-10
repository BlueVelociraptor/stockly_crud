<?php

namespace Database\Factories;

use App\Models\Product_Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product_Image>
 */
class Product_ImageFactory extends Factory
{
    protected $model = Product_Image::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "product_id" => null,
            "public_url" => fake()->url(),
            "public_id" => fake()->uuid(),
        ];
    }
}
