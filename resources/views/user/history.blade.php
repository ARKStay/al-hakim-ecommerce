<x-layouts.layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="container mx-auto mt-8">
        @forelse ($orders as $order)
            <div class="border border-gray-300 rounded-lg shadow-sm p-6 mb-6 bg-white">
                <!-- Header Order -->
                <div class="flex justify-between items-center border-b pb-3 mb-3">
                    <div>
                        <p class="text-sm text-gray-500">Order ID: <span
                                class="font-medium text-gray-800">#{{ $order->id }}</span></p>
                        <p class="text-sm text-gray-500">Order Date:
                            <span class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </p>
                    </div>
                    <span
                        class="px-3 py-1 rounded-full text-sm font-medium 
                        {{ $order->order_status === 'completed' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>

                <!-- Item Produk -->
                <div>
                    @foreach ($order->orderItems as $item)
                        <div class="flex items-start gap-4 border-b last:border-0 border-gray-200 py-4">
                            <!-- Gambar produk -->
                            <img src="{{ asset('storage/' . ($item->variant->variant_image ?? $item->product->image)) }}"
                                alt="{{ $item->product->name }}" class="w-20 h-20 object-cover rounded-md">

                            <!-- Detail produk -->
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                @if ($item->variant)
                                    <p class="text-xs text-gray-500">
                                        Variant:
                                        <span class="font-medium">{{ $item->variant->color ?? '-' }}</span>,
                                        <span class="font-medium">{{ $item->variant->size ?? '-' }}</span>
                                    </p>
                                @endif
                                <p class="text-xs text-gray-500">Quantity: {{ $item->quantity }}</p>
                                <p class="text-xs text-gray-500">Weight: {{ $item->variant->weight ?? '-' }} gr</p>
                            </div>

                            <!-- Harga -->
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-800">
                                    Rp {{ number_format($item->variant->price ?? $item->product->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Footer Order -->
                <div class="mt-4 text-sm text-gray-700 space-y-1">
                    <div class="flex justify-between">
                        <span>Shipping Cost</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-gray-900">
                        <span>Total Price</span>
                        <span class="text-red-500 text-lg">Rp
                            {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @empty
            <p class="my-32 text-center text-gray-500">No purchase history found.</p>
        @endforelse
    </div>
</x-layouts.layout>
