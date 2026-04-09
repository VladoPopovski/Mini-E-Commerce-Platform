<div class="mx-auto max-w-4xl">
    <div class="mb-4">
        <flux:link href="{{ route('market.index') }}">← Back to marketplace</flux:link>
    </div>

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

            <div class="mt-6">
                @auth
                    @if(auth()->user()->isBuyer())
                        <flux:button variant="primary" href="{{ route('cart.index') }}">
                            Add to Cart
                        </flux:button>
                    @endif
                @else
                    <flux:button variant="primary" href="{{ route('login') }}">
                        Login to purchase
                    </flux:button>
                @endauth
            </div>
        </div>
    </div>
</div>
