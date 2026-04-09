<?php

namespace App\Livewire\Buyer;

use App\Models\Order;
use Livewire\Component;

class OrderShow extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        abort_if($order->user_id !== auth()->id(), 403);
        $this->order = $order->load('items.product.vendor.user');
    }

    public function render()
    {
        return view('livewire.buyer.order-show')
            ->layout('layouts.app');
    }
}
