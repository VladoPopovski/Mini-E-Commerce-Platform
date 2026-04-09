<?php

namespace App\Livewire\Market;

use App\Actions\Cart\AddToCartAction;
use App\Models\Product;
use Livewire\Component;

class ProductShow extends Component
{
    public Product $product;
    public int $quantity = 1;

    public function addToCart(AddToCartAction $action): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        $cart = auth()->user()->cart;

        if (!$cart) {
            $cart = auth()->user()->cart()->create();
        }

        try {
            $action->execute($cart, $this->product, $this->quantity);
            session()->flash('success', 'Added to cart!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.market.product-show')
            ->layout('layouts.app');
    }
}
