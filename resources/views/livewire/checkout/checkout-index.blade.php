<div class="mx-auto max-w-2xl">
    <h1 class="mb-6 text-2xl font-bold text-zinc-900 dark:text-white">Checkout</h1>

    @if($this->error)
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">
            {{ $this->error }}
        </div>
    @endif

    @if($items->isEmpty())
        <div class="py-24 text-center text-zinc-500">
            Your cart is empty.
            <a href="{{ route('market.index') }}" class="ml-1 text-blue-600 underline">Browse products</a>
        </div>
    @else
        <!-- Order Summary -->
        <div class="mb-6 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <div class="bg-zinc-50 px-4 py-2 text-xs font-semibold uppercase text-zinc-500 dark:bg-zinc-800">
                Order Summary
            </div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                @foreach($items as $item)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium text-zinc-900 dark:text-white">{{ $item->product->name }}</p>
                            <p class="text-xs text-zinc-400">{{ $item->product->vendor->user->name }} × {{ $item->quantity }}</p>
                        </td>
                        <td class="px-4 py-3 text-right font-medium text-zinc-900 dark:text-white">
                            ${{ number_format($item->product->price * $item->quantity, 2) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <td class="px-4 py-3 font-semibold text-zinc-900 dark:text-white">Total</td>
                    <td class="px-4 py-3 text-right text-lg font-bold text-zinc-900 dark:text-white">
                        ${{ number_format($total, 2) }}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        @if($total > 999)
            <div class="mb-4 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400">
                ⚠️ Orders over $999 will be declined by the payment simulator.
            </div>
        @endif

        <!-- Payment Method -->
        <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="mb-4 font-semibold text-zinc-900 dark:text-white">Payment Method</h2>
            <div class="flex flex-col gap-3">
                @foreach(['credit_card' => 'Credit Card', 'wallet' => 'Wallet', 'bank_transfer' => 'Bank Transfer'] as $value => $label)
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input
                            type="radio"
                            wire:model="paymentMethod"
                            value="{{ $value }}"
                            class="text-blue-600"
                        />
                        <span class="text-zinc-700 dark:text-zinc-300">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <flux:button wire:click="placeOrder" variant="primary" class="w-full">
            Place Order
        </flux:button>
    @endif
</div>
