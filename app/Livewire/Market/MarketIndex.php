<?php

namespace App\Livewire\Market;

use App\Models\Product;
use App\Models\Vendor;
use Livewire\Component;
use Livewire\WithPagination;

class MarketIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $vendorId = '';
    public string $minPrice = '';
    public string $maxPrice = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingVendorId(): void { $this->resetPage(); }
    public function updatingMinPrice(): void { $this->resetPage(); }
    public function updatingMaxPrice(): void { $this->resetPage(); }

    public function render()
    {
        $products = Product::active()
            ->with('vendor.user')
            ->when($this->search, fn($q) => $q->where('name', 'ilike', '%' . $this->search . '%'))
            ->when($this->vendorId, fn($q) => $q->where('vendor_id', $this->vendorId))
            ->when($this->minPrice, fn($q) => $q->where('price', '>=', $this->minPrice))
            ->when($this->maxPrice, fn($q) => $q->where('price', '<=', $this->maxPrice))
            ->orderBy('name')
            ->paginate(12);

        $vendors = Vendor::with('user')->get();

        return view('livewire.market.market-index', compact('products', 'vendors'))
            ->layout('layouts.app');
    }
}
