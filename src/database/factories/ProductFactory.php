<?php

namespace Database\Factories;

use App\Models\Stock;
use App\Models\Product;
use App\Models\User;
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
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 500),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            Stock::factory()->create(['product_id' => $product->id]);
        });
    }
}
