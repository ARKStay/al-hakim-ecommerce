<x-layouts.layout>
    <x-slot:title>Payment and Proof Submission</x-slot:title>

    <section class="py-8 bg-white dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Shipping Address -->
            <div class="p-6 mb-8 border rounded-lg bg-white shadow-md dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-red-600 flex items-center">
                        Shipping Address
                    </h2>
                    <a href="{{ route('profile.edit', ['user' => Auth::id()]) }}"
                        class="text-blue-600 hover:underline text-sm">
                        Edit
                    </a>
                </div>
                <div class="mb-2">
                    <p class="text-sm text-gray-800 dark:text-gray-400">
                        <strong>Name:</strong> {{ $user->name }}
                    </p>
                    <p class="text-sm text-gray-800 dark:text-gray-400">
                        <strong>Phone:</strong> {{ $user->phone }}
                    </p>
                    <p class="text-sm text-gray-800 dark:text-gray-300">
                        <strong>Address:</strong> {{ $user->address }}
                    </p>
                </div>
            </div>

            <!-- Display Cart Items -->
            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <thead>
                    <tr class="text-sm font-medium text-gray-900 dark:text-white">
                        <th class="p-4 text-left">No</th>
                        <th class="p-4 text-left">Product</th>
                        <th class="p-4 text-left">Price</th>
                        <th class="p-4 text-left">Quantity</th>
                        <th class="p-4 text-left">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart_items as $index => $item)
                        <tr class="text-sm text-gray-900 dark:text-white">
                            <td class="p-4">{{ $index + 1 }}</td>
                            <td class="p-4 flex items-center space-x-4">
                                <!-- Product Image -->
                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                        alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h2 class="font-medium">{{ $item->product->name }}</h2>
                                </div>
                            </td>
                            <td class="p-4">Rp{{ number_format($item->product->price, 0, ',', '.') }}</td>
                            <td class="p-4">{{ $item->quantity }}</td>
                            <td class="p-4">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Total Payment -->
            <div class="mt-8 p-4 border rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Total Price</h2>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">
                        Rp{{ number_format($cart->price, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="mt-8">
                <p class="font-semibold text-gray-900 dark:text-white">Please transfer to the following account</p>
                <!-- List of Banks -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    @foreach ([
        'BRI' => 'bri-logo.png',
        'Mandiri' => 'mandiri-logo.png',
        'BCA' => 'bca-logo.png',
    ] as $bank => $icon)
                        <div class="flex items-center p-4 bg-gray-100 dark:bg-gray-800 rounded-lg shadow">
                            <!-- Bank Icon -->
                            <img src="{{ asset('storage/banks/' . $icon) }}" alt="{{ $bank }} Logo"
                                class="w-16 h-16 object-contain mr-4">
                            <!-- Bank Details -->
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $bank }}
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Account Number: <br><strong>123456789</strong>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Account Name: <br><strong>Al Hakim Store</strong>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Options -->
            <div class="mt-8">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Shipping Options</h2>
                <form method="POST" action="{{ route('user.payment') }}" enctype="multipart/form-data">
                    @csrf
                    <label for="shipping" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                        Choose shipping method:</label>
                    <select id="shipping" name="shipping"
                        class="block mt-1 p-2 border rounded-lg dark:bg-gray-800 dark:text-gray-400 mb-6"
                        onchange="updateTotal()" required>
                        <option value="" disabled selected>- Select Shipping Method -</option>
                        <option value="JNE" data-cost="15000">JNE - Rp 15.000</option>
                        <option value="J&T" data-cost="12000">J&T - Rp 12.000</option>
                        <option value="POS" data-cost="10000">POS Indonesia - Rp 10.000</option>
                        <option value="TIKI" data-cost="13000">TIKI - Rp 13.000</option>
                        <option value="Sicepat" data-cost="11000">Sicepat - Rp 11.000</option>
                    </select>   

                    <!-- Payment Proof -->
                    <div class="mt-8">
                        <p class="font-semibold text-gray-900 dark:text-white mb-2">Upload Payment Proof</p>
                        <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            aria-describedby="file_input_help" id="image" type="file" name="image"
                            onchange="previewImage()" required>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">PNG, JPG (MAX.
                            30MB).</p>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-300"><strong>*Note:</strong> Image should be
                            clear and of good quality.</p>
                    </div>

                    <!-- Recap of Purchase Details -->
                    <div class="mt-8 border p-8 rounded-xl bg-gray-50 dark:bg-gray-800 dark:border-gray-700 shadow-lg">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Summary</h2>
                        <table
                            class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg">
                            <thead>
                                <tr
                                    class="text-md font-semibold text-gray-900 dark:text-white bg-gray-200 dark:bg-gray-700">
                                    <th class="p-5 text-left">No</th>
                                    <th class="p-5 text-left">Product</th>
                                    <th class="p-5 text-left">Price</th>
                                    <th class="p-5 text-left">Quantity</th>
                                    <th class="p-5 text-left">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart_items as $index => $item)
                                    <tr class="text-md text-gray-900 dark:text-white border-b">
                                        <td class="p-5">{{ $index + 1 }}</td>
                                        <td class="p-5 flex items-center space-x-5">
                                            <div
                                                class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                    alt="{{ $item->product->name }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <h2 class="font-semibold">{{ $item->product->name }}</h2>
                                            </div>
                                        </td>
                                        <td class="p-5">
                                            Rp{{ number_format($item->product->price, 0, ',', '.') }}</td>
                                        <td class="p-5">{{ $item->quantity }}</td>
                                        <td class="p-5">Rp{{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-10 bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
                            <div class="flex justify-between items-center text-md text-gray-900 dark:text-white">
                                <span class="font-bold">Total Product Price:</span>
                                <span class="font-semibold">Rp{{ number_format($cart->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-md text-gray-900 dark:text-white mt-2">
                                <span><strong>Shipping Cost:</strong></span>
                                <span id="shipping-cost" class="font-semibold">Rp 0</span>
                            </div>
                            <div
                                class="flex justify-between items-center text-lg font-bold text-gray-900 dark:text-white mt-4">
                                <span>Total Payment:</span>
                                <span id="final-total">Rp{{ number_format($cart->price, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-md text-green-600 dark:text-green-400 font-medium text-center mt-3"
                                id="free-shipping-msg" style="display: none;">
                                🎉 Congratulations! You got free shipping!
                            </p>
                        </div>
                    </div>

                    <!-- Confirm Payment Button -->
                    <button type="submit"
                        class="mt-6 px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Confirm Payment
                    </button>
                </form>
            </div>
        </div>
        <script>
            function updateTotal() {
                let shippingSelect = document.getElementById("shipping");
                let selectedOption = shippingSelect.options[shippingSelect.selectedIndex];
                let shippingCost = parseInt(selectedOption.getAttribute("data-cost"));
                let productTotal = {{ $cart->price }};
                let finalTotal = productTotal + shippingCost;

                document.getElementById("shipping-cost").innerText = `Rp ${shippingCost.toLocaleString('id-ID')}`;
                document.getElementById("final-total").innerText = `Rp ${finalTotal.toLocaleString('id-ID')}`;
            }
        </script>
    </section>
</x-layouts.layout>
