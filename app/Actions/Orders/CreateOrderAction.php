<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Cart;
use App\Models\Order;

class CreateOrderAction
{
    public function execute(Cart $cart, PaymentMethod $paymentMethod): Order
    {
        $order = Order::create([
            'user_id'        => $cart->user_id,
            'status'         => OrderStatus::PENDING,
            'payment_method' => $paymentMethod,
            'total'          => $cart->items->sum(fn($i) => $i->product->price * $i->quantity),
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'vendor_id'  => $item->product->vendor_id,
                'quantity'   => $item->quantity,
                'unit_price' => $item->product->price,
            ]);
        }

        return $order;
    }
}
