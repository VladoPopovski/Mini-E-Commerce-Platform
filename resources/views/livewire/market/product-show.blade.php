<div class="mx-auto max-w-4xl">
    <div class="mb-4">
        <flux:link href="{{ route('market.index') }}" wire:navigate>← Back to marketplace</flux:link>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
            <a href="{{ route('cart.index') }}" class="ml-2 underline font-medium">View Cart →</a>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
        <img
            src="{{ $product->image_url }}"
            alt="{{ $product->name }}"
            class="w-full rounded-xl object-cover shadow"
        />

        <div>
            <p class="text-sm text-zinc-400">Sold by {{ $product->vendor->user->name }}</p>
            <h1 class="mt-1 text-3xl font-bold text-zinc-900 dark:text-white">{{ $product->name }}</h1>
            <p class="mt-4 text-zinc-600 dark:text-zinc-300">{{ $product->description }}</p>

            <div class="mt-6 flex items-center gap-4">
                <span class="text-3xl font-bold text-zinc-900 dark:text-white">
                    ${{ number_format($product->price, 2) }}
                </span>
                <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock' }}
                </span>
            </div>

            @if($product->stock > 0)
                <div class="mt-6 flex items-center gap-3">
                    <input
                        wire:model="quantity"
                        type="number"
                        min="1"
                        max="{{ $product->stock }}"
                        class="w-20 rounded-lg border border-zinc-300 px-3 py-2 text-center dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                    />
                    <flux:button wire:click="addToCart" variant="primary">
                        Add to Cart
                    </flux:button>
                </div>
            @else
                <flux:button disabled class="mt-6">Out of Stock</flux:button>
            @endif
        </div>
    </div>
</div>
