<div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Marketplace</h1>
        <span class="text-sm text-zinc-500">{{ $products->total() }} products</span>
    </div>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-4">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="Search products..."
            icon="magnifying-glass"
        />

        <flux:select wire:model.live="vendorId" placeholder="All vendors">
            <option value="">All vendors</option>
            @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}">{{ $vendor->user->name }}</option>
            @endforeach
        </flux:select>

        <flux:input
            wire:model.live.debounce.300ms="minPrice"
            type="number"
            placeholder="Min price"
            min="0"
        />

        <flux:input
            wire:model.live.debounce.300ms="maxPrice"
            type="number"
            placeholder="Max price"
            min="0"
        />
    </div>

    <!-- Product Grid -->
    @if($products->isEmpty())
        <div class="py-24 text-center text-zinc-500">
            No products found. Try adjusting your filters.
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($products as $product)
                <a href="{{ route('market.show', $product) }}"
                   class="group rounded-xl border border-zinc-200 bg-white shadow-sm transition hover:shadow-md dark:border-zinc-700 dark:bg-zinc-800">
                    <img
                        src="{{ $product->image_url }}"
                        alt="{{ $product->name }}"
                        class="h-48 w-full rounded-t-xl object-cover"
                    />
                    <div class="p-4">
                        <p class="text-xs text-zinc-400">{{ $product->vendor->user->name }}</p>
                        <h2 class="mt-1 font-semibold text-zinc-900 group-hover:text-blue-600 dark:text-white">
                            {{ $product->name }}
                        </h2>
                        <p class="mt-1 text-sm text-zinc-500 line-clamp-2">{{ $product->description }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-lg font-bold text-zinc-900 dark:text-white">
                                ${{ number_format($product->price, 2) }}
                            </span>
                            <span class="text-xs text-zinc-400">
                                {{ $product->stock }} in stock
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
</div>
