<?php

namespace App\Actions\Cart;

use App\Models\Cart;

class RemoveFromCartAction
{
    public function execute(Cart $cart, string $cartItemId): void
    {
        $cart->items()->findOrFail($cartItemId)->delete();
    }
}
