<div class="mx-auto max-w-5xl">
    <h1 class="mb-6 text-2xl font-bold text-zinc-900 dark:text-white">Orders</h1>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="py-24 text-center text-zinc-500">No orders yet.</div>
    @else
        <div class="flex flex-col gap-6">
            @foreach($orders as $order)
                <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
                    <!-- Order Header -->
                    <div class="flex items-center justify-between bg-zinc-50 px-4 py-3 dark:bg-zinc-800">
                        <div class="flex items-center gap-4">
                            <span class="font-mono text-xs text-zinc-500">
                                #{{ strtoupper(substr($order->id, -8)) }}
                            </span>
                            <span class="text-sm text-zinc-500">
                                {{ $order->created_at->format('M d, Y') }}
                            </span>
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                {{ $order->user->name }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="rounded-full px-2 py-1 text-xs font-medium
                                {{ match($order->status->value) {
                                    'paid'      => 'bg-green-100 text-green-700',
                                    'shipped'   => 'bg-blue-100 text-blue-700',
                                    'delivered' => 'bg-purple-100 text-purple-700',
                                    default     => 'bg-zinc-100 text-zinc-500',
                                } }}">
                                {{ ucfirst($order->status->value) }}
                            </span>

                            <!-- Status transition buttons -->
                            @if($order->status === \App\Enums\OrderStatus::PAID)
                                <flux:button size="sm" variant="primary"
                                             wire:click="updateStatus('{{ $order->id }}', 'shipped')"
                                             wire:confirm="Mark this order as shipped?">
                                    Mark Shipped
                                </flux:button>
                            @elseif($order->status === \App\Enums\OrderStatus::SHIPPED)
                                <flux:button size="sm" variant="primary"
                                             wire:click="updateStatus('{{ $order->id }}', 'delivered')"
                                             wire:confirm="Mark this order as delivered?">
                                    Mark Delivered
                                </flux:button>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items (vendor's products only) -->
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->product->image_url }}"
                                             class="h-10 w-10 rounded-lg object-cover" />
                                        <span class="font-medium text-zinc-900 dark:text-white">
                                                {{ $item->product->name }}
                                            </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-zinc-500">× {{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-right font-medium text-zinc-900 dark:text-white">
                                    ${{ number_format($item->unit_price * $item->quantity, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
</div>
