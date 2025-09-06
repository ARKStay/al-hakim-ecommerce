<x-dashboard.layout>
    <x-slot:title>Order Detail</x-slot:title>

    <div class="max-w-6xl mx-auto px-6 py-10">
        <nav class="text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
            <ol class="list-reset flex items-center space-x-2">
                <li>
                    <a href="{{ route('dashboard.shippeds.index') }}" class="text-blue-600 hover:underline">Shipped
                        Orders</a>
                </li>
                <li>/</li>
                <li class="text-gray-700 font-medium truncate max-w-[300px]">
                    Order #{{ $order->midtrans_order_id ?? $order->id }}
                </li>
            </ol>
        </nav>

        {{-- ORDER INFORMATION --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-xl shadow-md">
            {{-- CUSTOMER INFORMATION --}}
            <div>
                <h2 class="text-base font-semibold text-gray-700 mb-4 border-b pb-2">Customer Information</h2>
                <dl class="text-sm text-gray-700 space-y-3">
                    <div class="flex justify-between border-b pb-2">
                        <dt class="font-medium">Name:</dt>
                        <dd>{{ $order->user->name }}</dd>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <dt class="font-medium">Email:</dt>
                        <dd>{{ $order->user->email }}</dd>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <dt class="font-medium">Phone:</dt>
                        <dd>{{ $order->user->phone ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <dt class="font-medium">Address:</dt>
                        <dd class="text-right">{{ $order->user->address ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium">Location:</dt>
                        <dd class="text-right">
                            {{ $order->user->district_name ?? '-' }},
                            {{ $order->user->city_name ?? '-' }},
                            {{ $order->user->province_name ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- ORDER INFORMATION --}}
            <div>
                <h2 class="text-base font-semibold text-gray-700 mb-4 border-b pb-2">Order Information</h2>
                <dl class="text-sm text-gray-700 space-y-3">
                    <div class="flex justify-between border-b pb-2">
                        <dt class="font-medium">Order ID:</dt>
                        <dd>{{ $order->midtrans_order_id ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <dt class="font-medium">Status:</dt>
                        <dd>
                            @if ($order->order_status === 'pending')
                                <span class="text-yellow-600 font-semibold">Pending</span>
                            @elseif ($order->order_status === 'complete')
                                <span class="text-green-600 font-semibold">Completed</span>
                            @else
                                <span class="text-gray-500">Unknown</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <dt class="font-medium">Shipping:</dt>
                        <dd>{{ $order->shipping_method ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <dt class="font-medium">Payment:</dt>
                        <dd class="text-green-600 font-semibold">Paid</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium">Ordered At:</dt>
                        <dd>{{ $order->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- ORDERED ITEMS --}}
        <div class="mt-8 bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4">Ordered Items</h2>

            <div class="overflow-x-auto border rounded-lg shadow-sm">
                <table class="w-full text-sm text-left text-gray-700 bg-white border-collapse">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3">Image</th>
                            <th class="px-5 py-3">Product</th>
                            <th class="px-5 py-3">Color / Size</th>
                            <th class="px-5 py-3 text-center">Qty</th>
                            <th class="px-5 py-3 text-right">Price</th>
                            <th class="px-5 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/no-image.png') }}"
                                        class="w-14 h-14 object-cover rounded-lg border" alt="product image">
                                </td>
                                <td class="px-5 py-4 font-medium">{{ $item->product_name }}</td>
                                <td class="px-5 py-4">{{ $item->color ?? '-' }} / {{ $item->size ?? '-' }}</td>
                                <td class="px-5 py-4 text-center">{{ $item->quantity }}</td>
                                <td class="px-5 py-4 text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-right font-semibold">
                                    Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- TOTAL --}}
            <div class="text-right mt-4">
                @php
                    $total = $order->items->sum(fn($item) => $item->price * $item->quantity);
                @endphp
                <p class="text-lg font-bold">Total: Rp{{ number_format($total, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</x-dashboard.layout>
