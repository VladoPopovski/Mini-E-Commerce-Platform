<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Vendors + their products
        $this->call(VendorSeeder::class);

        // 2. Buyer accounts with carts
        $buyers = User::factory()->count(5)->create();

        $products = Product::all();

        foreach ($buyers as $buyer) {
            $cart = Cart::factory()->create(['user_id' => $buyer->id]);

            // 2-3 random products per cart, no duplicates
            $randomProducts = $products->random(rand(2, 3));

            foreach ($randomProducts as $product) {
                CartItem::factory()->create([
                    'cart_id'    => $cart->id,
                    'product_id' => $product->id,
                    'quantity'   => rand(1, 3),
                ]);
            }
        }
    }
}
