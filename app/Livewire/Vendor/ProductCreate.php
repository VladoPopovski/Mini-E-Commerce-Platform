<?php

namespace App\Livewire\Vendor;

use App\Actions\Products\CreateProductAction;
use App\Enums\ProductStatus;
use Livewire\Component;

class ProductCreate extends Component
{
    public string $name = '';
    public string $description = '';
    public string $price = '';
    public int $stock = 0;
    public string $image_url = '';
    public string $status = 'active';

    public function save(CreateProductAction $action): void
    {
        $this->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0.01',
            'stock'       => 'required|integer|min:0',
            'image_url'   => 'required|url',
            'status'      => 'required|in:draft,active,archived',
        ]);

        $action->execute(auth()->user()->vendor, [
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'stock'       => $this->stock,
            'image_url'   => $this->image_url,
            'status'      => ProductStatus::from($this->status),
        ]);

        session()->flash('success', 'Product created.');
        $this->redirect(route('vendor.products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.vendor.product-create')
            ->layout('layouts.app');
    }
}
