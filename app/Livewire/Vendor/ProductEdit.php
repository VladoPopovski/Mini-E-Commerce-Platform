<?php

namespace App\Livewire\Vendor;

use App\Actions\Products\UpdateProductAction;
use App\Enums\ProductStatus;
use App\Models\Product;
use Livewire\Component;

class ProductEdit extends Component
{
    public Product $product;

    public string $name = '';
    public string $description = '';
    public string $price = '';
    public int $stock = 0;
    public string $image_url = '';
    public string $status = 'active';

    public function mount(Product $product): void
    {
        // Ensure vendor owns this product
        abort_if($product->vendor_id !== auth()->user()->vendor?->id, 403);

        $this->product   = $product;
        $this->name        = $product->name;
        $this->description = $product->description;
        $this->price       = (string) $product->price;
        $this->stock       = $product->stock;
        $this->image_url   = $product->image_url;
        $this->status      = $product->status->value;
    }

    public function save(UpdateProductAction $action): void
    {
        $this->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0.01',
            'stock'       => 'required|integer|min:0',
            'image_url'   => 'required|url',
            'status'      => 'required|in:draft,active,archived',
        ]);

        $action->execute($this->product, [
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'stock'       => $this->stock,
            'image_url'   => $this->image_url,
            'status'      => ProductStatus::from($this->status),
        ]);

        session()->flash('success', 'Product updated.');
        $this->redirect(route('vendor.products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.vendor.product-edit')
            ->layout('layouts.app');
    }
}
