<x-layouts.layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="container mx-auto p-6 mb-32">
        @if ($orders->isEmpty())
            <p class="text-gray-600">You have no orders yet.</p>
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
                                        @foreach ($order->cart->items as $item)
                                            <li class="text-gray-800">
                                                {{ optional($item->product)->name ?? 'N/A' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-4 py-2 border">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-2 border">
                                    @if ($order->payment_status == 'approved')
                                        <span class="text-green-600 font-semibold">Approved</span>
                                    @elseif ($order->payment_status == 'pending')
                                        <span class="text-yellow-600 font-semibold">Pending</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Rejected</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border">
                                    @if ($order->payment_status == 'rejected')
                                        <span class="text-red-600 font-semibold">-</span>
                                    @elseif ($order->order_status == 'complete')
                                        <span class="text-green-600 font-semibold">Complete</span>
                                    @else
                                        <span class="text-gray-600 font-semibold">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border">
                                    <button 
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700"
                                        onclick="openModal({{ $order->id }})"
                                    >
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div id="orderModal"
        class="fixed inset-0 bg-gray-800 bg-opacity-50 items-center justify-center transition-opacity duration-300 hidden"
        data-visible="false" 
        onclick="closeModal()">
        <div class="bg-white rounded-lg w-2/3 p-6 shadow-lg" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-xl font-semibold">Order Details</h3>
                <button onclick="closeModal()" class="text-gray-600 hover:text-gray-800">&times;</button>
            </div>
            <div id="modalContent" class="mt-4">
                <!-- Konten rincian belanja akan dimasukkan di sini -->
            </div>
            <div class="text-right mt-6">
                <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function openModal(orderId) {
            const modal = document.getElementById('orderModal');
            const orders = @json($orders);
            const order = orders.find(o => o.id === orderId);

            // Base URL for images
            const baseURL = "{{ asset('storage') }}";

            // Format harga menggunakan Intl.NumberFormat
            function formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                }).format(value);
            }

            // Generate modal content
            const modalContent = document.getElementById('modalContent');
            let total = 0;
            modalContent.innerHTML = `
                <table class="min-w-full border-collapse text-gray-800">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border text-left">Product</th>
                            <th class="px-4 py-2 border text-left">Image</th>
                            <th class="px-4 py-2 border text-left">Quantity</th>
                            <th class="px-4 py-2 border text-left">Price</th>
                            <th class="px-4 py-2 border text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${order.cart.items.map(item => {
                            const itemTotal = item.quantity * item.price;
                            total += itemTotal;
                            return `
                                <tr>
                                    <td class="px-4 py-2 border">${item.product ? item.product.name : 'N/A'}</td>
                                    <td class="px-4 py-2 border">
                                        ${item.product && item.product.image ? 
                                            `<img src="${baseURL}/${item.product.image}" alt="${item.product.name}" class="w-20 h-20 object-cover">` 
                                            : 'N/A'}
                                    </td>
                                    <td class="px-4 py-2 border">${item.quantity}</td>
                                    <td class="px-4 py-2 border">${formatCurrency(item.price)}</td>
                                    <td class="px-4 py-2 border">${formatCurrency(itemTotal)}</td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
                <div class="mt-4 text-right font-semibold">
                    <p><strong>Total Amount:</strong> ${formatCurrency(total)}</p>
                </div>
            `;

            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'opacity-100');
        }

        function closeModal() {
            const modal = document.getElementById('orderModal');
            modal.classList.remove('flex', 'opacity-100');
            modal.classList.add('hidden');
        }
    </script>
</x-layouts.layout>
