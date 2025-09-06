<x-layouts.layout>
    <x-slot:title>Your Cart</x-slot:title>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
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

    <section class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-6xl mx-auto px-6">
            <h1 class="text-3xl font-bold mb-8 text-gray-800 dark:text-white">Shopping Cart</h1>

            @if ($cartitems && $cartitems->count() > 0)
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach ($cartitems as $index => $cartitem)
                                @php
                                    $variant = $cartitem->variant;
                                    $product = $variant?->product;
                                @endphp

                                <tr>
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-start space-x-4">
                                            <img src="{{ asset('storage/' . ($product->image ?? 'no-image.jpg')) }}"
                                                alt="{{ $product->name ?? 'Unknown Product' }}"
                                                class="w-16 h-16 object-cover border rounded shadow-sm">

                                            <img src="{{ asset('storage/' . ($variant->variant_image ?? 'no-image.jpg')) }}"
                                                alt="{{ $variant->color ?? 'Variant' }}"
                                                class="w-16 h-16 object-cover border rounded shadow-sm">

                                            <div class="text-sm">
                                                <div class="font-bold text-gray-800 dark:text-white cursor-pointer"
                                                    title="{{ $product->name }}">
                                                    {{ \Illuminate\Support\Str::limit($product->name, 25) }}
                                                </div>
                                                <div class="text-gray-600 dark:text-gray-400">
                                                    Warna: {{ $variant->color }}<br>
                                                    Ukuran: {{ $variant->size }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-gray-800 dark:text-white">
                                        Rp{{ number_format($cartitem->price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-gray-800 dark:text-white">
                                        {{ $cartitem->quantity }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        Rp{{ number_format($cartitem->price * $cartitem->quantity, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <form action="{{ route('user.cart.delete', $cartitem->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:text-red-800 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Total & Checkout Section -->
                <div class="mt-8 p-6 rounded-lg bg-white dark:bg-gray-800 shadow flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-xl font-semibold text-gray-800 dark:text-white">
                        Total Price:
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400 ml-2">
                            Rp{{ number_format($cart->total_price, 0, ',', '.') }}
                        </span>
                    </div>

                    <a href="{{ url('confirm_check_out') }}"
                        class="px-6 py-3 text-base font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 shadow">
                        Proceed to Checkout
                    </a>
                </div>

                <!-- Tombol Kembali Belanja -->
                <div class="mt-6 flex justify-start">
                    <a href="/user/products"
                        class="inline-block px-6 py-3 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                        ← Continue Shopping
                    </a>
                </div>
            @else
                <div class="flex flex-col items-center justify-center text-center mt-24 mb-24 space-y-4">
                    <p class="text-lg text-gray-600 dark:text-gray-400">Your cart is empty. Let's fill it up!</p>
                    <a href="/user/products"
                        class="mt-4 px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 shadow">
                        Continue Shopping
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-layouts.layout>
