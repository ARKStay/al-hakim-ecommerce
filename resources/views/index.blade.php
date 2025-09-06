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
                    toast: true,
                    position: 'center'
                });
            });
        </script>
    @endif

    <div class="max-w-screen-xl mx-auto px-4 lg:px-6 py-10">

        {{-- Carousel --}}
        <div x-data="{ currentSlide: 0, interval: null }" x-init="interval = setInterval(() => { currentSlide = (currentSlide + 1) % {{ max(count($banners), 1) }} }, 5000);" class="relative overflow-hidden rounded-xl shadow-md mb-12">
            <div class="flex transition-transform duration-700 ease-out"
                :style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
                @forelse ($banners as $banner)
                    <div class="w-full flex-shrink-0">
                        <div class="aspect-[2/1] bg-gray-100">
                            @if ($banner->image)
                                <img src="{{ asset('storage/' . $banner->image) }}" class="w-full h-full object-cover"
                                    alt="Banner">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500">
                                    No image available
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="w-full aspect-[2/1] flex items-center justify-center bg-gray-100 text-gray-500">
                        No image available
                    </div>
                @endforelse
            </div>

            {{-- Navigation --}}
            <button @click="currentSlide = (currentSlide - 1 + {{ count($banners) }}) % {{ count($banners) }}"
                class="absolute top-1/2 left-3 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-800 p-2 rounded-full shadow">
                &#10094;
            </button>
            <button @click="currentSlide = (currentSlide + 1) % {{ count($banners) }}"
                class="absolute top-1/2 right-3 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-800 p-2 rounded-full shadow">
                &#10095;
            </button>

            {{-- Dots --}}
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
                @foreach ($banners as $index => $banner)
                    <button @click="currentSlide = {{ $index }}"
                        :class="currentSlide === {{ $index }} ? 'bg-blue-600' : 'bg-gray-400'"
                        class="h-2.5 w-2.5 rounded-full transition duration-300"></button>
                @endforeach
            </div>
        </div>

        {{-- Feature Icons --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center mb-16">
            @php
                $features = [
                    ['img' => 'fashion.png', 'label' => 'Modest & Elegant'],
                    ['img' => 'fabric.png', 'label' => 'Premium Fabric'],
                    ['img' => 'tunic.png', 'label' => 'Sharia Compliant'],
                    ['img' => 'alterations.png', 'label' => 'Perfect Tailored Fit'],
                ];
            @endphp

            @foreach ($features as $feature)
                <div class="flex flex-col items-center">
                    <img src="{{ asset('storage/banks/' . $feature['img']) }}" class="w-12 h-12 mb-2"
                        alt="{{ $feature['label'] }}">
                    <p class="text-sm font-medium text-gray-700">{{ $feature['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- About Section --}}
        <div class="text-center max-w-3xl mx-auto mb-20 px-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">About Toko Al Hakim</h2>
            <p class="text-gray-600 leading-relaxed">
                Toko Al Hakim is a clothing store that offers a variety of Muslim fashion choices that are not only
                comfortable to wear but also meaningful. By prioritizing quality materials, elegant designs, and a touch
                of
                modernity, we present products suitable for daily wear and special occasions.
            </p>
            <p class="text-gray-600 mt-4 leading-relaxed">
                The name "Al Hakim" means "The Most Wise", and that is the value we carry in every product — a wise
                choice, full of consideration, emphasizing simplicity and meaningful beauty. Toko Al Hakim is where
                style meets values.
            </p>
        </div>

        {{-- Trending Products --}}
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Trending Products</h2>
            <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($trendingProducts as $product)
                    @php
                        $variants = $product->variants;
                        $minPrice = $variants->min('price');
                        $maxPrice = $variants->max('price');
                        $soldCount = $product->sold ?? 0;
                    @endphp

                    <div
                        class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col">
                        <a href="{{ route('products.detail', $product->slug) }}">
                            <div class="aspect-[4/3] bg-gray-100">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        class="w-full h-full object-cover" alt="{{ $product->name }}">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-400">No image
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="p-4 flex flex-col flex-grow">
                            <a href="{{ route('products.detail', $product->slug) }}"
                                class="text-base font-semibold text-gray-800 hover:text-blue-600 truncate"
                                title="{{ $product->name }}">
                                {{ Str::limit($product->name, 40) }}
                            </a>

                            <div class="mt-1 flex justify-between items-center text-sm text-gray-500">
                                <div class="flex items-center space-x-1">
                                    <span>⭐</span>
                                    <span>{{ number_format($product->average_rating, 1) }}</span>
                                </div>
                                <div>{{ $soldCount > 0 ? "Terjual $soldCount" : '' }}</div>
                            </div>

                            <div class="mt-3">
                                @if ($minPrice === $maxPrice)
                                    <p class="text-lg font-bold text-gray-900">
                                        Rp{{ number_format($minPrice, 0, ',', '.') }}
                                    </p>
                                @else
                                    <p class="text-sm font-medium text-gray-800">
                                        Rp{{ number_format($minPrice, 0, ',', '.') }} -
                                        Rp{{ number_format($maxPrice, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>

                            <div class="mt-auto pt-4">
                                <a href="{{ route('products.detail', $product->slug) }}"
                                    class="block text-center bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-700 transition">
                                    View Product
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-layouts.layout>
