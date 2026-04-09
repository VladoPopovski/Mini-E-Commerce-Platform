<?php

namespace App\Livewire\Vendor;

use Livewire\Component;
use Livewire\WithPagination;

class ProductsIndex extends Component
{
    use WithPagination;

    public function deleteProduct(string $id): void
    {
        $product = auth()->user()->vendor->products()->findOrFail($id);
        $product->delete();
        session()->flash('success', 'Product deleted.');
    }

    public function render()
    {
        $products = auth()->user()->vendor->products()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.vendor.products-index', compact('products'))
            ->layout('layouts.app');
    }
}
