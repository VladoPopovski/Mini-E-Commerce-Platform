<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Validation\ValidationException;

class UpdateOrderStatusAction
{
    public function execute(Order $order, OrderStatus $newStatus): Order
    {
        if (!$order->canTransitionTo($newStatus)) {
            throw ValidationException::withMessages([
                'status' => "Cannot transition from {$order->status->value} to {$newStatus->value}.",
            ]);
        }

        $order->update(['status' => $newStatus]);

        return $order->fresh();
    }
}
