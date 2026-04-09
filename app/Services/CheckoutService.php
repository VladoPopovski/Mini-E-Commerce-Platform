<?php

namespace App\Services;

use App\Actions\Orders\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(
        private readonly CreateOrderAction      $createOrder,
        private readonly PaymentSimulatorService $paymentSimulator,
    ) {}

    public function checkout(Cart $cart, PaymentMethod $paymentMethod): Order
    {
        // Validate cart is not empty
        if ($cart->items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Your cart is empty.',
            ]);
        }

        // Validate stock for every item
        foreach ($cart->items as $item) {
            if ($item->product->stock < $item->quantity) {
                throw ValidationException::withMessages([
                    'stock' => "'{$item->product->name}' only has {$item->product->stock} units left.",
                ]);
            }
        }

        return DB::transaction(function () use ($cart, $paymentMethod) {
            $order = $this->createOrder->execute($cart, $paymentMethod);

            $success = $this->paymentSimulator->process(
                (float) $order->total,
                $paymentMethod->value
            );

            if (!$success) {
                // Payment failed — roll back via exception so DB::transaction reverts
                throw ValidationException::withMessages([
                    'payment' => 'Payment declined. Orders over $999 cannot be processed.',
                ]);
            }

            // Decrement stock
            foreach ($cart->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

            // Mark order paid
            $order->update(['status' => OrderStatus::PAID]);

            // Clear cart
            $cart->items()->delete();

            return $order->fresh();
        });
    }
}
