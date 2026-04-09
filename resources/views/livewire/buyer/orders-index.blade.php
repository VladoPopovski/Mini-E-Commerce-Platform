<div class="mx-auto max-w-4xl">
    <h1 class="mb-6 text-2xl font-bold text-zinc-900 dark:text-white">My Orders</h1>

    @if($orders->isEmpty())
        <div class="py-24 text-center text-zinc-500">No orders yet.</div>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Order</th>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                @foreach($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs text-zinc-500">
                            #{{ strtoupper(substr($order->id, -8)) }}
                        </td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-1 text-xs font-medium
                                    {{ match($order->status->value) {
                                        'paid'      => 'bg-green-100 text-green-700',
                                        'shipped'   => 'bg-blue-100 text-blue-700',
                                        'delivered' => 'bg-purple-100 text-purple-700',
                                        default     => 'bg-zinc-100 text-zinc-500',
                                    } }}">
                                    {{ ucfirst($order->status->value) }}
                                </span>
                        </td>
                        <td class="px-4 py-3 text-right font-medium">${{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-3 text-right">
                            <flux:button size="sm" href="{{ route('buyer.orders.show', $order) }}" wire:navigate>
                                View
                            </flux:button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
</div>
