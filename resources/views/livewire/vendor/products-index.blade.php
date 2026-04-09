<div>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">My Products</h1>
        <flux:button href="{{ route('vendor.products.create') }}" variant="primary" wire:navigate>
            + Add Product
        </flux:button>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if($products->isEmpty())
        <div class="py-24 text-center text-zinc-500">
            You haven't listed any products yet.
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Product</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Price</th>
                    <th class="px-4 py-3 text-right">Stock</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                @foreach($products as $product)
                    <tr>
                        <td class="px-4 py-3 flex items-center gap-3">
                            <img src="{{ $product->image_url }}" class="h-10 w-10 rounded-lg object-cover" />
                            <span class="font-medium text-zinc-900 dark:text-white">{{ $product->name }}</span>
                        </td>
                        <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-1 text-xs font-medium
                                    {{ $product->status->value === 'active' ? 'bg-green-100 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                                    {{ ucfirst($product->status->value) }}
                                </span>
                        </td>
                        <td class="px-4 py-3 text-right">${{ number_format($product->price, 2) }}</td>
                        <td class="px-4 py-3 text-right">{{ $product->stock }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <flux:button size="sm" href="{{ route('vendor.products.edit', $product) }}" wire:navigate>
                                    Edit
                                </flux:button>
                                <flux:button size="sm" variant="danger"
                                             wire:click="deleteProduct('{{ $product->id }}')"
                                             wire:confirm="Delete this product?">
                                    Delete
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $products->links() }}</div>
    @endif
</div>
