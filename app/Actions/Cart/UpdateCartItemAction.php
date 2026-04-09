<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Validation\ValidationException;

class UpdateCartItemAction
{
    public function execute(Cart $cart, string $cartItemId, int $quantity): CartItem
    {
        $item = $cart->items()->with('product')->findOrFail($cartItemId);

        if ($quantity < 1) {
            $item->delete();
            return $item;
        }

        if ($item->product->stock < $quantity) {
            throw ValidationException::withMessages([
                'stock' => "Only {$item->product->stock} units available.",
            ]);
        }

        $item->update(['quantity' => $quantity]);
        return $item->fresh();
    }
}
