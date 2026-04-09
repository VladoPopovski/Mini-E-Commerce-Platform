<?php

namespace App\Livewire\Vendor;

use App\Actions\Orders\UpdateOrderStatusAction;
use App\Enums\OrderStatus;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersIndex extends Component
{
    use WithPagination;

    public function updateStatus(string $orderId, string $status, UpdateOrderStatusAction $action): void
    {
        $order = Order::findOrFail($orderId);

        // Ensure this order contains at least one of this vendor's products
        $vendorId = auth()->user()->vendor->id;
        $hasItem  = $order->items()->where('vendor_id', $vendorId)->exists();

        abort_if(!$hasItem, 403);

        try {
            $action->execute($order, OrderStatus::from($status));
            session()->flash('success', 'Order status updated.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', collect($e->errors())->flatten()->first());
        }
    }

    public function render()
    {
        $vendorId = auth()->user()->vendor->id;

        $orders = Order::whereHas('items', fn($q) => $q->where('vendor_id', $vendorId))
            ->with(['items' => fn($q) => $q->where('vendor_id', $vendorId)->with('product'), 'user'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.vendor.orders-index', compact('orders'))
            ->layout('layouts.app');
    }
}
