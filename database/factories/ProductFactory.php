<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Vendor;
use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    // Realistic product names so marketplace looks real
    private array $productNames = [
        'Wireless Noise-Cancelling Headphones', 'Mechanical Keyboard', 'USB-C Hub',
        'Leather Wallet', 'Stainless Steel Water Bottle', 'Yoga Mat',
        'Coffee Grinder', 'Bamboo Cutting Board', 'Desk Lamp', 'Running Shoes',
        'Backpack 30L', 'Sunglasses UV400', 'Protein Powder Vanilla', 'Notebook A5',
        'Ceramic Mug Set', 'Phone Stand', 'Resistance Bands', 'Scented Candle',
        'Portable Charger 20000mAh', 'Kitchen Scale',
    ];

    public function definition(): array
    {
        return [
            'vendor_id'   => Vendor::factory(),
            'name'        => fake()->unique()->randomElement($this->productNames),
            'description' => fake()->paragraph(3),
            'price'       => fake()->randomFloat(2, 9.99, 499.99),
            'stock'       => fake()->numberBetween(5, 100),
            'image_url'   => 'https://picsum.photos/seed/' . fake()->word() . '/640/480',
            'status'      => ProductStatus::ACTIVE,
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => ProductStatus::DRAFT]);
    }

    public function outOfStock(): static
    {
        return $this->state(['stock' => 0]);
    }

    public function expensive(): static
    {
        return $this->state(['price' => fake()->randomFloat(2, 1000, 2000)]);
    }
}
