<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $product = Product::factory()->create();

        return [
            'order_id'   => Order::factory(),
            'product_id' => $product->id,
            'vendor_id'  => $product->vendor_id,
            'quantity'   => fake()->numberBetween(1, 4),
            'unit_price' => $product->price,
        ];
    }
}
