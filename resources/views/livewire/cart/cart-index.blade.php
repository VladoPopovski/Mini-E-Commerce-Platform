<div class="mx-auto max-w-4xl">
    <h1 class="mb-6 text-2xl font-bold text-zinc-900 dark:text-white">Your Cart</h1>

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    @if($groupedItems->isEmpty())
        <div class="py-24 text-center text-zinc-500">
            Your cart is empty.
            <a href="{{ route('market.index') }}" class="ml-1 text-blue-600 underline">Browse products</a>
        </div>
    @else
        <div class="flex flex-col gap-6">
            @foreach($groupedItems as $vendorName => $items)
                <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
                    <div class="bg-zinc-50 px-4 py-2 text-xs font-semibold uppercase text-zinc-500 dark:bg-zinc-800">
                        {{ $vendorName }}
                    </div>
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                        @foreach($items as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->product->image_url }}" class="h-12 w-12 rounded-lg object-cover" />
                                        <div>
                                            <p class="font-medium text-zinc-900 dark:text-white">{{ $item->product->name }}</p>
                                            <p class="text-xs text-zinc-400">${{ number_format($item->product->price, 2) }} each</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input
                                        type="number"
                                        value="{{ $item->quantity }}"
                                        min="1"
                                        max="{{ $item->product->stock }}"
                                        wire:change="updateQuantity('{{ $item->id }}', $event.target.value)"
                                        class="w-16 rounded-lg border border-zinc-300 px-2 py-1 text-center dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                                    />
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-zinc-900 dark:text-white">
                                    ${{ number_format($item->product->price * $item->quantity, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <flux:button size="sm" variant="danger"
                                                 wire:click="removeItem('{{ $item->id }}')">
                                        Remove
                                    </flux:button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <td colspan="2" class="px-4 py-2 text-xs text-zinc-500">Subtotal</td>
                            <td class="px-4 py-2 text-right font-semibold text-zinc-900 dark:text-white">
                                ${{ number_format($items->sum(fn($i) => $i->product->price * $i->quantity), 2) }}
                            </td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            @endforeach

            <!-- Order Total & Checkout -->
            <div class="flex items-center justify-between rounded-xl border border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
                <div>
                    <p class="text-sm text-zinc-500">Order Total</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">${{ number_format($total, 2) }}</p>
                </div>
                <flux:button href="{{ route('checkout.index') }}" variant="primary" wire:navigate>
                    Proceed to Checkout →
                </flux:button>
            </div>
        </div>
    @endif
</div>
