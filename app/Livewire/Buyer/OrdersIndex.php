<?php

namespace App\Livewire\Buyer;

use Livewire\Component;
use Livewire\WithPagination;

class OrdersIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $orders = auth()->user()->orders()
            ->with('items.product')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.buyer.orders-index', compact('orders'))
            ->layout('layouts.app');
    }
}
