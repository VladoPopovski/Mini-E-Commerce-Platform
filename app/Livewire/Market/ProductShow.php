<?php

namespace App\Livewire\Market;

use App\Models\Product;
use Livewire\Component;

class ProductShow extends Component
{
    public Product $product;

    public function render()
    {
        return view('livewire.market.product-show')
            ->layout('layouts.app');
    }
}
