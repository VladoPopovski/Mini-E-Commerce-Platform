<?php

namespace App\Livewire\Cart;

use App\Actions\Cart\RemoveFromCartAction;
use App\Actions\Cart\UpdateCartItemAction;
use Livewire\Component;

class CartIndex extends Component
{
    public function updateQuantity(string $itemId, int $quantity, UpdateCartItemAction $action): void
    {
        $cart = auth()->user()->cart;

        if (!$cart) return;

        try {
            $action->execute($cart, $itemId, $quantity);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', collect($e->errors())->flatten()->first());
        }
    }

    public function removeItem(string $itemId, RemoveFromCartAction $action): void
    {
        $cart = auth()->user()->cart;

        if (!$cart) return;

        $action->execute($cart, $itemId);
    }

    public function render()
    {
        $cart = auth()->user()->cart?->load('items.product.vendor.user');

        $groupedItems = $cart
            ? $cart->items->groupBy(fn($item) => $item->product->vendor->user->name)
            : collect();

        $total = $cart
            ? $cart->items->sum(fn($item) => $item->product->price * $item->quantity)
            : 0;

        return view('livewire.cart.cart-index', compact('groupedItems', 'total'))
            ->layout('layouts.app');
    }
}
