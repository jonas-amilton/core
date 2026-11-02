<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Catalog\Models\Product;

/**
 * @extends Factory<Product>
 */
final class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 40, 2500);

        return [
            'name' => 'Resistência '.fake()->word(),
            'slug' => null,
            'sku' => strtoupper(fake()->bothify('TL-??-####')),
            'price' => number_format($price, 2, '.', ''),
            'stock' => fake()->numberBetween(0, 40),
            'description' => fake()->paragraph(),
            'position' => fake()->numberBetween(0, 20),
        ];
    }
}
