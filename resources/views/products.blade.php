<x-layouts.layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">

        <!-- Search Form -->
        <form class="max-w-md mx-auto mb-5">
            <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="search" placeholder="Search products..." name="search" autocomplete="off"
                    value="{{ request('search') }}"
                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <button type="submit"
                    class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
            </div>
        </form>

        <!-- Product Grid -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($products as $product)
                <div class="relative flex flex-col rounded-lg border border-gray-200 bg-white p-6 shadow-sm h-full">
                    <!-- Category Tag -->
                    <p class="absolute -top-2 -left-2 z-10 inline-block rounded-lg bg-blue-500 px-3 py-1 text-xs font-medium text-white shadow-lg hover:bg-blue-600">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </p>
                    <div class="h-56 w-full">
                        <a href="{{ route('products.detail', $product->slug) }}">
                            @if ($product->image)
                                <img class="mx-auto h-full object-cover rounded" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <div class="h-56 w-full flex items-center justify-center bg-gray-100 text-gray-500">
                                    <p>No image available</p>
                                </div>
                            @endif
                        </a>
                    </div>
                    <div class="pt-6 flex-grow">
                        <a href="{{ route('products.detail', $product->slug) }}" 
                            class="block text-lg font-semibold leading-tight text-gray-900 hover:underline truncate"
                            style="max-width: 100%;" title="{{ $product->name }}">
                            {{ Str::limit($product->name, 30, '...') }}
                        </a>
                        
                        <!-- Grid untuk info biar sejajar -->
                        <div class="mt-2 grid grid-cols-1 gap-1 text-sm text-gray-500">
                            <p><span class="font-medium text-gray-700">Color:</span> {{ $product->color ?? 'No Color' }}</p>
                            <p><span class="font-medium text-gray-700">Stock:</span> {{ $product->stock > 0 ? $product->stock : 'Out of stock' }}</p>
                            <p><span class="font-medium text-gray-700">Ratings:</span> ⭐ {{ number_format($product->average_rating, 1) }} ({{ $product->total_ratings }} reviews)</p>
                        </div>
        
                        <!-- Harga & Ukuran sejajar -->
                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-2xl font-extrabold text-gray-900">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-500">Size: {{ $product->sizes->name ?? '-' }}</p>
                        </div>
                    </div>
        
                    <!-- Button tetap di bawah -->
                    <div class="mt-auto pt-4">
                        <a href="{{ route('products.detail', $product->slug) }}" 
                            class="inline-flex items-center justify-center bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 w-full">
                            View Product
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 my-10">
                    <p class="text-lg font-semibold text-gray-500">No products available at the moment.</p>
                </div>
            @endforelse
        </div>
        
    </div>
</x-layouts.layout>
