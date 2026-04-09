<div class="mx-auto max-w-3xl">
    <div class="mb-6">
        <flux:link href="{{ route('buyer.orders.index') }}" wire:navigate>← My Orders</flux:link>
        <h1 class="mt-2 text-2xl font-bold text-zinc-900 dark:text-white">
            Order #{{ strtoupper(substr($order->id, -8)) }}
        </h1>
        <p class="text-sm text-zinc-500">Placed {{ $order->created_at->format('M d, Y \a\t H:i') }}</p>
    </div>

    <!-- Status -->
    <div class="mb-6 flex items-center gap-3">
        <span class="rounded-full px-3 py-1 text-sm font-medium
            {{ match($order->status->value) {
                'paid'      => 'bg-green-100 text-green-700',
                'shipped'   => 'bg-blue-100 text-blue-700',
                'delivered' => 'bg-purple-100 text-purple-700',
                default     => 'bg-zinc-100 text-zinc-500',
            } }}">
            {{ ucfirst($order->status->value) }}
        </span>
        <span class="text-sm text-zinc-500">via {{ ucwords(str_replace('_', ' ', $order->payment_method->value)) }}</span>
    </div>

    <!-- Items -->
    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 mb-6">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Product</th>
                <th class="px-4 py-3 text-left">Vendor</th>
                <th class="px-4 py-3 text-right">Qty</th>
                <th class="px-4 py-3 text-right">Unit Price</th>
                <th class="px-4 py-3 text-right">Subtotal</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
            @foreach($order->items as $item)
                <tr>
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $item->product->name }}</td>
                    <td class="px-4 py-3 text-zinc-500">{{ $item->vendor->user->name }}</td>
                    <td class="px-4 py-3 text-right">{{ $item->quantity }}</td>
                    <td class="px-4 py-3 text-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-4 py-3 text-right font-medium">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot class="bg-zinc-50 dark:bg-zinc-800">
            <tr>
                <td colspan="4" class="px-4 py-3 font-semibold text-zinc-900 dark:text-white">Total</td>
                <td class="px-4 py-3 text-right text-lg font-bold text-zinc-900 dark:text-white">
                    ${{ number_format($order->total, 2) }}
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
