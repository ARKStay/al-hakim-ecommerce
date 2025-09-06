<x-dashboard.layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <section class="pt-2 pb-6 bg-white md:pt-4 md:pb-12 dark:bg-gray-900">
        <div class="max-w-screen-xl px-4 mx-auto">
            {{-- Breadcrumb --}}
            <nav class="text-base text-gray-500 mb-2 md:mt-4" aria-label="Breadcrumb">
                <ol class="list-reset flex">
                    <li>
                        <a href="/dashboard/products" class="text-blue-500 hover:underline">Products</a>
                        <span class="mx-2">/</span>
                    </li>
                    <li class="text-gray-700 dark:text-gray-300 truncate max-w-[400px]">{{ $product->name }}</li>
                </ol>
            </nav>

            {{-- Grid Utama --}}
            <div class="grid md:grid-cols-2 gap-10">
                {{-- Gambar utama dan thumbnail --}}
                <div>
                    <div class="w-full aspect-[3/2] bg-gray-100 flex items-center justify-center border rounded">
                        <img id="mainImage" class="object-contain h-full"
                            src="{{ asset('storage/' . ($product->image ?? 'placeholder.jpg')) }}"
                            alt="{{ $product->name }}">
                    </div>
                    <div class="flex mt-4 gap-2 flex-wrap">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" onclick="resetMainImage()"
                                class="w-16 h-16 object-cover border cursor-pointer rounded thumbnail" alt="Main Image">
                        @endif
                        @foreach ($product->variants->unique('color') as $variant)
                            @if ($variant->variant_image)
                                <img src="{{ asset('storage/' . $variant->variant_image) }}"
                                    data-color="{{ $variant->color }}" onclick="selectColor('{{ $variant->color }}')"
                                    class="w-16 h-16 object-cover border cursor-pointer rounded thumbnail variant-thumbnail"
                                    alt="{{ $variant->color }}">
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Detail Produk --}}
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white truncate max-w-[90%]">
                        {{ $product->name }}</h1>
                    <div class="mt-2 text-green-600 text-3xl font-semibold" id="price">
                        Rp{{ number_format($product->variants->first()->price, 0, ',', '.') }}
                    </div>

                    {{-- Warna --}}
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Available Colors:</h3>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach ($product->variants->unique('color') as $variant)
                                <span class="px-3 py-1 rounded border text-sm font-medium bg-white text-gray-800">
                                    {{ ucfirst($variant->color) }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Ukuran --}}
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Available Sizes:</h3>
                        <div class="flex flex-wrap gap-2 mt-2 min-h-[40px]" id="sizeOptions">
                            @foreach ($product->variants as $variant)
                                <span class="px-3 py-1 text-sm rounded border bg-white text-gray-800">
                                    {{ ucfirst($variant->color) }} - {{ $variant->size }} |
                                    Rp{{ number_format($variant->price, 0, ',', '.') }} | Stock: {{ $variant->stock }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Rating --}}
                    <div class="mt-6 p-4 border rounded bg-gray-50 dark:bg-gray-800 dark:border-gray-700 max-w-md">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Product Stats:</h3>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            <p>Average Rating: <strong>{{ number_format($average_rating, 1) ?? '-' }}</strong></p>
                            <p>Total Ratings: <strong>{{ $total_ratings ?? 0 }}</strong></p>
                            <p>Sold: <strong>{{ $product->sold ?? 0 }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-10 border-gray-300" />

            {{-- Deskripsi & Ulasan --}}
            <div class="grid md:grid-cols-3 gap-10">
                <div class="md:col-span-2">
                    <div class="p-6 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Product Description</h2>
                        <div class="mt-4 text-gray-600 dark:text-gray-300 prose dark:prose-invert">
                            {!! $product->description ?? '<p>No description available.</p>' !!}
                        </div>
                    </div>
                </div>
                <div>
                    <div class="p-6 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Customer Reviews</h2>
                        <div class="mt-4 space-y-4 max-h-96 overflow-y-auto">
                            @forelse ($product->ratings as $rating)
                                <div class="border-t pt-4">
                                    <div class="flex justify-between text-sm text-gray-700 dark:text-gray-400">
                                        <span><strong>{{ $rating->user->username }}</strong></span>
                                        <span>{{ $rating->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="mt-1 text-yellow-500">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i < $rating->rating ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $rating->comment ?? '-' }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500">No reviews yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Optional: Bisa tambahin JS variant selector kalau admin mau preview warna dan gambar --}}
    <script>
        const variants = @json($product->variants);

        function selectColor(color) {
            const selectedVariant = variants.find(v => v.color === color);
            if (selectedVariant && selectedVariant.variant_image) {
                document.getElementById('mainImage').src = '/storage/' + selectedVariant.variant_image;
            }
        }

        function resetMainImage() {
            document.getElementById('mainImage').src = '/storage/{{ $product->image }}';
        }
    </script>
</x-dashboard.layout>
