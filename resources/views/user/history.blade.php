<x-layouts.layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="container mx-auto mt-6">

        @forelse ($orders->where('order_status', 'complete') as $order)
            <div class="border border-gray-300 rounded-lg shadow-sm p-4 mb-6">
                <!-- Order Header -->
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-sm text-gray-500">Order Date:
                            {{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="flex justify-between items-center mt-2">
                    <span class="text-sm font-medium text-gray-700">Order Status:</span>
                    <span class="text-sm font-medium text-green-500">Completed</span>
                </div>

                <!-- List Items -->
                <div class="mt-4">
                    @foreach ($order->orderItems as $item)
                        <div class="flex items-start gap-4 border-b border-gray-200 pb-4 mb-4">
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}"
                                class="w-16 h-16 object-cover rounded-md">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium">{{ $item->product->name }}</h3>
                                <p class="text-xs text-gray-500">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-700">Rp
                                    {{ number_format($item->product->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Footer -->
                <div class="mt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Shipping Cost:</span>
                        <span class="text-sm font-medium text-gray-700">Rp
                            {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-sm font-medium text-gray-700">Total Price:</span>
                        <span class="text-lg font-bold text-red-500">Rp
                            {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Rate Order Button (hides if already rated) -->
                @if ($order->ratings && $order->ratings->where('user_id', auth()->id())->isEmpty())
                    <div class="mt-4 text-right">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
                            data-order-id="{{ $order->id }}" onclick="openRatingModal(this)">
                            Rate Order
                        </button>
                    </div>
                @else
                    <div class="mt-4 text-right text-gray-500">
                        <span>Order already rated</span>
                    </div>
                @endif
            </div>
        @empty
            <p class="my-32 text-center text-gray-500">No completed orders found.</p>
        @endforelse

        <!-- Pesan Notifikasi -->
        <p class="my-8 text-center text-sm text-gray-500">
            Note: Your order history may be deleted after a few days.
        </p>

        <!-- Terimakasih Notifikasi -->
        <p class="my-4 text-center text-lg text-green-500">
            Thank you for your order!
        </p>
    </div>

    <!-- Modal for Rating -->
    <div id="ratingModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden z-50">
        <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-96 mx-auto mt-10 transform translate-y-1/2">
            <h2 class="text-lg font-semibold mb-4">Rate Your Order</h2>
            <form id="ratingForm" action="{{ route('user.rating.store') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" id="order_id">
                <div class="mb-4">
                    <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                    <select id="rating" name="rating"
                        class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm">
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700">Comment</label>
                    <textarea id="comment" name="comment" class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md"
                        onclick="closeRatingModal()">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRatingModal(button) {
            const orderId = button.getAttribute('data-order-id');
            document.getElementById('order_id').value = orderId;
            document.getElementById('ratingModal').classList.remove('hidden');
        }

        function closeRatingModal() {
            document.getElementById('ratingModal').classList.add('hidden');
        }
    </script>
</x-layouts.layout>
