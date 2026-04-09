<div class="mx-auto max-w-2xl">
    <div class="mb-6">
        <flux:link href="{{ route('vendor.products.index') }}" wire:navigate>← My Products</flux:link>
        <h1 class="mt-2 text-2xl font-bold text-zinc-900 dark:text-white">Add Product</h1>
    </div>

    <form wire:submit="save" class="flex flex-col gap-5">
        <flux:input wire:model="name" label="Product Name" required />
        <flux:textarea wire:model="description" label="Description" rows="4" required />

        <div class="grid grid-cols-2 gap-4">
            <flux:input wire:model="price" label="Price ($)" type="number" step="0.01" min="0.01" required />
            <flux:input wire:model="stock" label="Stock Quantity" type="number" min="0" required />
        </div>

        <flux:input wire:model="image_url" label="Image URL" type="url" placeholder="https://..." required />

        <flux:select wire:model="status" label="Status">
            <option value="active">Active</option>
            <option value="draft">Draft</option>
            <option value="archived">Archived</option>
        </flux:select>

        <div class="flex justify-end gap-3">
            <flux:button href="{{ route('vendor.products.index') }}" wire:navigate>Cancel</flux:button>
            <flux:button type="submit" variant="primary">Create Product</flux:button>
        </div>
    </form>
</div>
