<?php

namespace App\Livewire\Checkout;

use App\Enums\PaymentMethod;
use App\Services\CheckoutService;
use Livewire\Component;

class CheckoutIndex extends Component
{
    public string $paymentMethod = 'credit_card';
    public ?string $error = null;

    public function placeOrder(CheckoutService $checkoutService): void
    {
        $this->error = null;

        $cart = auth()->user()->cart?->load('items.product.vendor');

        if (!$cart || $cart->items->isEmpty()) {
            $this->error = 'Your cart is empty.';
            return;
        }

        try {
            $order = $checkoutService->checkout(
                $cart,
                PaymentMethod::from($this->paymentMethod)
            );

            $this->redirect(route('buyer.orders.show', $order), navigate: true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->error = collect($e->errors())->flatten()->first();
        }
    }

    public function render()
    {
        $cart  = auth()->user()->cart?->load('items.product.vendor.user');
        $items = $cart?->items ?? collect();
        $total = $items->sum(fn($i) => $i->product->price * $i->quantity);

        return view('livewire.checkout.checkout-index', compact('items', 'total'))
            ->layout('layouts.app');
    }
}
