<x-layouts.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 5000,
                    position: 'mid'
                });
            });
        </script>
    @endif
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
        <!-- Carousel -->
        <div class="relative w-full mx-auto overflow-hidden rounded-lg mb-8" x-data="{ currentSlide: 0, interval: null }"
            x-init="interval = setInterval(() => { currentSlide = (currentSlide + 1) % {{ count($banners) }} }, 5000);">
            <!-- Slides -->
            <div class="flex transition-transform duration-700 ease-out"
                :style="'transform: translateX(-' + (currentSlide * 100) + '%);'">
                @foreach ($banners as $banner)
                    <div class="w-full flex-shrink-0">
                        @if ($banner->image)
                            <!-- Gunakan aspect ratio 16:9 -->
                            <div class="w-full aspect-[2/1]">
                                <img src="{{ asset('storage/' . $banner->image) }}"
                                    class="w-full h-full object-cover rounded-lg" alt="Banner Image">
                            </div>
                        @else
                            <!-- Placeholder jika tidak ada gambar -->
                            <div
                                class="w-full aspect-[16/9] flex items-center justify-center bg-gray-200 text-gray-500 rounded-lg">
                                <p>No image available</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Navigation Arrows -->
            <button @click="currentSlide = (currentSlide - 1 + {{ count($banners) }}) % {{ count($banners) }}"
                class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-gray-800/50 text-white p-2 rounded-full hover:bg-gray-800/70">
                &#10094;
            </button>
            <button @click="currentSlide = (currentSlide + 1) % {{ count($banners) }}"
                class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-gray-800/50 text-white p-2 rounded-full hover:bg-gray-800/70">
                &#10095;
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                @foreach ($banners as $index => $banner)
                    <button @click="currentSlide = {{ $index }}"
                        :class="{
                            'bg-white': currentSlide === {{ $index }},
                            'bg-gray-500': currentSlide !== {{ $index }}
                        }"
                        class="h-3 w-3 rounded-full"></button>
                @endforeach
            </div>
        </div>

        <!-- Products Section -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($products->take(8) as $product)
                <div class="relative rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <!-- Category Tag -->
                    <p
                        class="absolute -top-2 -left-2 z-10 inline-block rounded-lg bg-blue-500 px-3 py-1 text-xs font-medium text-white shadow-lg hover:bg-blue-600">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </p>
                    <div class="h-56 w-full">
                        <a href="{{ route('user.products.detail', $product->slug) }}">
                            @if ($product->image)
                                <img class="mx-auto h-full object-cover rounded"
                                    src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <div class="h-56 w-full flex items-center justify-center bg-gray-100 text-gray-500">
                                    <p>No image available</p>
                                </div>
                            @endif
                        </a>
                    </div>
                    <div class="pt-6">
                        <a href="{{ route('user.products.detail', $product->slug) }}"
                            class="text-lg font-semibold leading-tight text-gray-900 hover:underline">
                            {{ $product->name }}
                        </a>
                        <div class="mt-2 text-sm text-gray-500">
                            <p><span class="font-medium text-gray-700">Color:</span>
                                {{ $product->color ?? 'No Color' }}</p>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-2xl font-extrabold text-gray-900">
                                Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">Size: {{ $product->sizes->name ?? '-' }}</p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('user.products.detail', $product->slug) }}"
                                class="inline-flex items-center bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700">
                                View Product
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Tombol Show More -->
        <div class="mt-6 flex justify-center">
            <a href="/user/products"
                class="rounded-lg border border-gray-700 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 
        hover:bg-gray-100 hover:text-blue-700 hover:border-blue-700 focus:outline-none focus:ring-4 focus:ring-gray-200">
                Show more
            </a>
        </div>

    </div>
</x-layouts.layout>
