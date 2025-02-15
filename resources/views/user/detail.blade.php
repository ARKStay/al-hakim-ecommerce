<x-layouts.layout>
    <x-slot:title>Product Details</x-slot:title>
    <section class="py-8 bg-white md:py-16 dark:bg-gray-900 antialiased">
        <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
            <!-- Back to Products Button -->
            <div class="mb-4">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/user/' }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-lg hover:bg-gray-300 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                    ← Back to Products
                </a>
            </div>

            <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
                <!-- Product Image -->
                <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                    @if ($product->image)
                        <img class="w-full" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" />
                    @else
                        <div class="w-full h-64 bg-gray-100 flex items-center justify-center text-gray-500">
                            <p>No image available</p>
                        </div>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="mt-6 sm:mt-8 lg:mt-0">
                    <!-- Category Tag -->
                    <div class="mb-3">
                        <p
                            class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-900">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </p>
                    </div>

                    <!-- Product Title -->
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                        {{ $product->name }}
                    </h1>

                    <!-- Average Rating (Pindah ke sini) -->
                    <div class="mt-2 text-gray-700 dark:text-gray-300">
                        @if ($product->ratings->count() > 0)
                            <div class="flex items-center">
                                @php
                                    $avgRating = $product->ratings->avg('rating');
                                @endphp
                                @for ($i = 0; $i < 5; $i++)
                                    <i
                                        class="fas fa-star {{ $i < floor($avgRating) ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="ml-2 text-yellow-500">{{ number_format($avgRating, 1) }} / 5</span>
                                <span class="ml-2 text-gray-500">({{ $product->ratings->count() }} reviews)</span>
                            </div>
                        @else
                            <span class="text-gray-500">No ratings yet</span>
                        @endif
                    </div>

                    <!-- Product Price -->
                    <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
                        <p class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white">
                            Rp{{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8">
                        @auth
                            <form action="{{ url('user/detail') }}/{{ $product->slug }}" method="POST"
                                class="flex space-x-4">
                                @csrf
                                <!-- Input Quantity -->
                                <div>
                                    <label for="quantity" class="sr-only">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" min="1"
                                        max="{{ $product->stock }}" value="1" required
                                        class="w-20 text-center border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:ring-primary-500">
                                </div>

                                <!-- Input Hidden Product ID -->
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <!-- Add to Cart Button -->
                                <button type="submit"
                                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 flex items-center justify-center">
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <p class="text-gray-700 dark:text-gray-300">Please <a href="/login"
                                    class="text-primary-600 hover:underline">login</a> to add items to your cart or buy
                                products.</p>
                        @endauth
                    </div>

                    <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />

                    <!-- Product Information Table -->
                    <div class="mt-6">
                        <table class="min-w-full border border-gray-300 rounded-lg text-gray-700 dark:text-gray-300">
                            <tbody>
                                <tr>
                                    <td class="py-2 px-4 font-medium">Color</td>
                                    <td class="py-2 px-4">{{ $product->color ?? 'Not available' }}</td>
                                </tr>
                                <tr class="bg-gray-100 dark:bg-gray-800">
                                    <td class="py-2 px-4 font-medium">Size</td>
                                    <td class="py-2 px-4">{{ $product->sizes->name ?? 'Not available' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-4 font-medium">Stock</td>
                                    <td class="py-2 px-4">{{ $product->stock }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Product Description -->
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Product Description</h2>
                        <div class="mt-4 text-gray-500 dark:text-gray-400 prose dark:prose-invert">
                            {!! $product->description !!}
                        </div>
                    </div>

                    <!-- Ratings and Comments Section -->
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ratings and Reviews</h2>

                        <!-- List of Comments -->
                        <div class="mt-4">
                            @foreach ($product->ratings as $rating)
                                <div class="border-t border-gray-300 pt-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="text-sm font-semibold">{{ $rating->user->username }}</div>
                                        <div class="text-xs text-gray-500">{{ $rating->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="text-yellow-500">Rating:
                                            @for ($i = 0; $i < 5; $i++)
                                                <i
                                                    class="fas fa-star {{ $i < $rating->rating ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </span>
                                    </div>
                                    <div class="mt-2 text-gray-600 dark:text-gray-400">
                                        {{ $rating->comment }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </section>
</x-layouts.layout>
