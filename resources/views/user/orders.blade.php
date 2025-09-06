<x-layouts.layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="container mx-auto p-6 mb-32">
        @if ($orders->isEmpty())
            <p class="text-gray-600 my-12 flex flex-col items-center justify-center">
                <span class="text-2xl font-semibold">No Orders Found</span>
                <span class="text-lg text-gray-500 mt-2">
                    Looks like you haven't placed any orders yet. Start shopping now and grab your favorite items!
                </span>
            </p>
        @else
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 border text-left">Order ID</th>
                            <th class="px-4 py-2 border text-left">Product(s)</th>
                            <th class="px-4 py-2 border text-left">Order Date</th>
                            <th class="px-4 py-2 border text-left">Payment Status</th>
                            <th class="px-4 py-2 border text-left">Order Status</th>
                            <th class="px-4 py-2 border text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-2 border">{{ $order->id }}</td>
                                <td class="px-4 py-2 border">
                                    <ul class="list-disc pl-5">
                                        @foreach ($order->items as $item)
                                            <li class="text-gray-800">
                                                {{ $item->product_name }}
                                                @if ($item->color || $item->size)
                                                    <span class="text-gray-500">
                                                        ({{ $item->color }} {{ $item->size }})
                                                    </span>
                                                @endif
                                                (x{{ $item->quantity }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-4 py-2 border">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-2 border">
                                    @if ($order->payment_status === 'paid')
                                        <span class="text-green-600 font-semibold">Paid</span>
                                    @elseif ($order->payment_status === 'pending')
                                        <span class="text-yellow-600 font-semibold">Pending</span>
                                    @else
                                        <span
                                            class="text-red-600 font-semibold">{{ ucfirst($order->payment_status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border">
                                    @if ($order->order_status === 'shipped')
                                        <form action="{{ route('orders.markReceived', $order->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin barang sudah diterima?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-700">
                                                Mark as Received
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="font-semibold {{ $order->order_status === 'completed' ? 'text-green-600' : 'text-gray-600' }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border flex items-center gap-2">
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm"
                                        onclick="openDetailsModal({{ $order->id }})">
                                        View Details
                                    </button>

                                    @if ($order->order_status === 'completed')
                                        @php
                                            $reviewed = $order->items->every(
                                                fn($item) => $item->product->ratings
                                                    ->where('user_id', auth()->id())
                                                    ->count() > 0,
                                            );
                                        @endphp

                                        @if (!$reviewed)
                                            <button
                                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-700 text-sm"
                                                onclick="openReviewModal({{ $order->id }})">
                                                Give Review
                                            </button>
                                        @else
                                            <span class="text-gray-600 text-sm">Reviewed</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- CSRF token untuk JS --}}
    <script>
        const csrfToken = '{{ csrf_token() }}';
    </script>

    {{-- Modal Order Details --}}
    <div id="orderDetailsModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl w-11/12 md:w-3/4 lg:w-2/3 shadow-xl max-h-[85vh] overflow-y-auto relative p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-bold text-gray-800">Order Details</h3>
                <button onclick="closeDetailsModal()"
                    class="text-gray-500 hover:text-gray-800 text-2xl font-bold">&times;</button>
            </div>
            <div id="detailsContent" class="space-y-4"></div>
            <div class="flex justify-end mt-4">
                <button onclick="closeDetailsModal()"
                    class="bg-gray-400 text-white px-5 py-2 rounded-lg hover:bg-gray-600 font-semibold">Close</button>
            </div>
        </div>
    </div>

    {{-- Modal Give Review --}}
    <div id="orderReviewModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-60 
        items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl w-11/12 md:w-3/4 lg:w-2/3 shadow-xl max-h-[85vh] overflow-y-auto relative p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-bold text-gray-800">Give Review</h3>
                <button onclick="closeReviewModal()"
                    class="text-gray-500 hover:text-gray-800 text-2xl font-bold">&times;</button>
            </div>
            <div id="reviewContent" class="space-y-4"></div>
        </div>
    </div>

    <script>
        const orders = @json($orders->load('items.product'));

        function formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(value);
        }

        // ----- Order Details -----
        function openDetailsModal(orderId) {
            const order = orders.find(o => o.id === orderId);
            const baseURL = "{{ asset('storage') }}";
            const content = document.getElementById('detailsContent');
            const modal = document.getElementById('orderDetailsModal');

            // tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            content.innerHTML = `
        ${(order.items ?? []).map(item => `
                            <div class="flex flex-col md:flex-row items-center border rounded-lg p-3 gap-4">
                                <img src="${item.product?.image ? `${baseURL}/${item.product.image}` : 'https://via.placeholder.com/100'}"
                                    alt="${item.product_name}" class="w-24 h-24 object-contain rounded-lg">
                                <div class="flex-1 space-y-1">
                                    <p class="font-semibold text-gray-800">${item.product_name}</p>
                                    <p class="text-gray-600 text-sm">Color: ${item.color ?? '-'}</p>
                                    <p class="text-gray-600 text-sm">Size: ${item.size ?? '-'}</p>
                                    <p class="text-gray-600 text-sm">Quantity: ${item.quantity}</p>
                                    <p class="font-semibold text-gray-800 text-sm">Price: ${formatCurrency(item.price)}</p>
                                </div>
                            </div>
                        `).join('')}
        <div class="border-t pt-3 flex justify-between font-semibold text-gray-800">
            <span>Shipping Cost:</span>
            <span>${formatCurrency(order.shipping_cost)}</span>
        </div>
        <div class="flex justify-between font-semibold text-gray-800">
            <span>Total Amount:</span>
            <span>${formatCurrency(order.total_price)}</span>
        </div>
    `;
        }

        function closeDetailsModal() {
            document.getElementById('orderDetailsModal').classList.add('hidden');
        }

        // ----- Give Review -----
        function openReviewModal(orderId) {
            const order = orders.find(o => o.id === orderId);
            const baseURL = "{{ asset('storage') }}";
            const content = document.getElementById('reviewContent');

            content.innerHTML = `
                <form action="/orders/${orderId}/review" method="POST" class="space-y-6">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    ${(order.items ?? []).map(item => `
                                            <div class="flex flex-col md:flex-row items-start border rounded-xl p-4 gap-4">
                                                <img src="${item.product?.image ? `${baseURL}/${item.product.image}` : 'https://via.placeholder.com/100'}"
                                                    alt="${item.product_name}" class="w-24 h-24 object-contain rounded-lg">
                                                <div class="flex-1 space-y-2">
                                                    <p class="font-semibold text-gray-800">${item.product_name}</p>
                                                    <input type="hidden" name="ratings[${item.product.id}][product_id]" value="${item.product.id}">
                                                    <div class="flex items-center gap-2">
                                                        <label class="text-sm font-medium">Rating:</label>
                                                        <select name="ratings[${item.product.id}][rating]" class="border rounded px-2 py-1 w-20">
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium">Comment:</label>
                                                        <textarea name="ratings[${item.product.id}][comment]" rows="3" class="border rounded-lg w-full px-2 py-1" placeholder="Write your comment..."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        `).join('')}
                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-semibold">
                            Submit Review
                        </button>
                    </div>
                </form>
            `;
            document.getElementById('orderReviewModal').classList.remove('hidden');
        }

        function closeReviewModal() {
            const modal = document.getElementById('orderReviewModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</x-layouts.layout>
