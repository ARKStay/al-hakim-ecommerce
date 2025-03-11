<x-layouts.layout>
    <x-slot:title>Your Cart</x-slot:title>
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'bottom-end'
                });
            });
        </script>
    @endif
    <section class="py-8 bg-white dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($cart_items && $cart_items->count() > 0)
                <!-- Cart Table -->
                <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <thead>
                        <tr class="text-sm font-medium text-gray-900 dark:text-white">
                            <th class="p-4 text-left">No</th>
                            <th class="p-4 text-left">Product</th>
                            <th class="p-4 text-left">Price</th>
                            <th class="p-4 text-left">Quantity</th>
                            <th class="p-4 text-left">Total</th>
                            <th class="p-4 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart_items as $index => $cart_item)
                            <tr class="text-sm text-gray-900 dark:text-white">
                                <td class="p-4">{{ $index + 1 }}.</td>
                                <td class="p-4 flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div
                                        class="w-16 h-16 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $cart_item->product->image) }}"
                                            alt="{{ $cart_item->product->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h2 class="font-medium">{{ $cart_item->product->name }}</h2>
                                    </div>
                                </td>
                                <td class="p-4">Rp{{ number_format($cart_item->product->price, 0, ',', '.') }}</td>
                                <td class="p-4">{{ $cart_item->quantity }}</td>
                                <td class="p-4">Rp{{ number_format($cart_item->price, 0, ',', '.') }}</td>
                                <td class="p-4">
                                    <form action="{{ route('user.cart.delete', $cart_item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="w-8 h-8 text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Order Summary -->
                <div class="mt-8 p-4 border rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Order Summary</h2>
                    <div class="flex justify-between">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Total Price</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                            Rp{{ number_format($cart->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Proceed to Checkout button (only if cart is not empty) -->
                <div class="mt-8 flex justify-between">
                    <a href="/user/products"
                        class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">Continue
                        Shopping</a>
                    <a href="{{ url('confirm_check_out') }}"
                        class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Proceed
                        to
                        Checkout</a>
                </div>
            @else
                <!-- Display message when cart is empty -->
                <div class="flex flex-col items-center justify-center mt-24 mb-24 space-y-4">
                    <p class="text-lg text-gray-600 dark:text-gray-400">Your cart is empty. Let's fill it up!</p>
                </div>

                <!-- Only show Continue Shopping button when cart is empty -->
                <div class="mt-8 flex justify-center">
                    <a href="/user/products"
                        class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md transition duration-300">
                        Continue Shopping
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-layouts.layout>
