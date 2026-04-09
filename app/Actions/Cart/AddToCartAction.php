<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Validation\ValidationException;

class AddToCartAction
{
    public function execute(Cart $cart, Product $product, int $quantity = 1): CartItem
    {
        if ($product->stock < $quantity) {
            throw ValidationException::withMessages([
                'stock' => "Only {$product->stock} units available.",
            ]);
        }

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $newQty = $item->quantity + $quantity;

            if ($product->stock < $newQty) {
                throw ValidationException::withMessages([
                    'stock' => "Only {$product->stock} units available.",
                ]);
            }

            $item->update(['quantity' => $newQty]);
            return $item->fresh();
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'quantity'   => $quantity,
        ]);
    }
}
