<x-layouts.layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <section class="pt-2 pb-6 bg-white md:pt-4 md:pb-12 dark:bg-gray-900">
        <div class="max-w-screen-xl px-4 mx-auto">
            {{-- Breadcrumb --}}
            <nav class="text-base text-gray-500 mb-2 md:mt-4" aria-label="Breadcrumb">
                <ol class="list-reset flex">
                    <li>
                        <a href="/" class="text-blue-500 hover:underline">Home</a>
                        <span class="mx-2">/</span>
                    </li>
                    <li>
                        <a href="/products" class="text-blue-500 hover:underline">Products</a>
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
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Choose Color:</h3>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach ($product->variants->unique('color') as $variant)
                                @php
                                    $colorStock = $product->variants->where('color', $variant->color)->sum('stock');
                                @endphp
                                <button type="button" onclick="selectColor('{{ $variant->color }}')"
                                    class="px-3 py-1 rounded border text-sm font-medium transition variant-color-btn
                                           {{ $colorStock > 0 ? 'bg-white text-gray-800 hover:bg-gray-100' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}"
                                    {{ $colorStock <= 0 ? 'disabled' : '' }} data-color="{{ $variant->color }}">
                                    {{ ucfirst($variant->color) }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Ukuran --}}
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Choose Size:</h3>
                        <div class="flex flex-wrap gap-2 mt-2 min-h-[40px]" id="sizeOptions"></div>
                    </div>

                    {{-- Stok --}}
                    <div class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                        Stock: <span id="stock">{{ $product->variants->first()->stock }}</span>
                    </div>

                    {{-- Informasi Pilihan dan Total Harga --}}
                    <div class="mt-6 p-4 border rounded bg-gray-50 dark:bg-gray-800 dark:border-gray-700 max-w-md">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Selected Options:</h3>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            <p>Color: <span id="selectedColorText">-</span></p>
                            <p>Size: <span id="selectedSizeText">-</span></p>
                            <p>Quantity: <span id="selectedQtyText">1</span></p>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 dark:text-gray-300">Total Price:</p>
                            <p id="totalPrice" class="text-2xl font-bold text-green-600 dark:text-green-400">
                                Rp{{ number_format($product->variants->first()->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Login --}}
                    <form method="POST" action="{{ url('user/detail/' . $product->slug) }}"
                        class="mt-6 flex flex-col gap-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="color" id="selectedColorInput">
                        <input type="hidden" name="size" id="selectedSizeInput">

                        <div class="flex items-center gap-3">
                            <label for="quantity" class="text-sm text-gray-700 dark:text-gray-300">Quantity:</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1"
                                class="w-20 text-center border-gray-300 rounded-lg shadow-sm dark:bg-gray-800 dark:text-white dark:border-gray-700"
                                oninput="updateTotalPrice()">
                        </div>

                        <div class="flex gap-4 mt-4">
                            <a href="{{ route('login') }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded font-medium w-full text-center block">
                                Login to Purchase
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <hr class="my-10 border-gray-300" />

            {{-- Deskripsi & Ulasan --}}
            <div class="grid md:grid-cols-3 gap-10">
                <div class="md:col-span-2">
                    <div class="p-6 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Product Description</h2>
                        <div class="mt-4 text-gray-600 dark:text-gray-300 prose dark:prose-invert max-h-40 overflow-hidden relative"
                            id="descriptionBox">
                            <div id="descriptionContent">{!! $product->description !!}</div>
                            <div id="fadeOverlay"
                                class="absolute bottom-0 left-0 w-full h-10 bg-gradient-to-t from-white dark:from-gray-800 to-transparent hidden">
                            </div>
                        </div>
                        <button id="toggleDescription" class="mt-3 text-blue-600 hover:underline text-sm">Show
                            more</button>
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
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $rating->comment }}</p>
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

    {{-- JS Script --}}
    <script>
        const variants = @json($product->variants);
        let selectedColor = '';
        let selectedSize = '';

        function updateTotalPrice() {
            const qty = parseInt(document.getElementById('quantity').value) || 1;
            const selected = variants.find(v => v.color === selectedColor && v.size === selectedSize);
            const price = selected ? selected.price : 0;
            const total = qty * price;
            document.getElementById('totalPrice').textContent = 'Rp' + total.toLocaleString('id-ID');
            document.getElementById('selectedQtyText').textContent = qty;
        }

        function resetMainImage() {
            document.getElementById('mainImage').src = `/storage/{{ $product->image }}`;
            document.querySelectorAll('.variant-color-btn').forEach(btn => btn.classList.remove('ring', 'ring-offset-2',
                'ring-primary-500'));
            selectedColor = '';
            selectedSize = '';
            document.getElementById('selectedColorInput').value = '';
            document.getElementById('selectedSizeInput').value = '';
            document.getElementById('selectedColorText').textContent = '-';
            document.getElementById('selectedSizeText').textContent = '-';
            document.getElementById('sizeOptions').innerHTML = '';
            document.getElementById('price').textContent =
                'Rp{{ number_format($product->variants->first()->price, 0, ',', '.') }}';
            document.getElementById('stock').textContent = '{{ $product->variants->first()->stock }}';
            updateTotalPrice();
        }

        function selectColor(color) {
            selectedColor = color;
            document.getElementById('selectedColorInput').value = color;
            document.getElementById('selectedColorText').textContent = color;

            const variant = variants.find(v => v.color === color);
            if (variant && variant.variant_image) {
                document.getElementById('mainImage').src = `/storage/${variant.variant_image}`;
            }

            document.querySelectorAll('.variant-color-btn').forEach(btn => {
                btn.classList.remove('ring', 'ring-offset-2', 'ring-primary-500');
                if (btn.dataset.color === color) {
                    btn.classList.add('ring', 'ring-offset-2', 'ring-primary-500');
                }
            });

            const sizeContainer = document.getElementById('sizeOptions');
            sizeContainer.innerHTML = '';
            const sizes = variants.filter(v => v.color === color);
            sizes.forEach(v => {
                const disabled = v.stock === 0 ? 'disabled' : '';
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = v.size;
                button.className =
                    `size-btn px-3 py-1 text-sm font-medium rounded border ${disabled ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-gray-100'}`;
                button.disabled = v.stock === 0;
                button.dataset.size = v.size;
                button.onclick = () => selectSize(v.size);
                sizeContainer.appendChild(button);
            });

            const firstAvailable = sizes.find(v => v.stock > 0);
            if (firstAvailable) {
                selectSize(firstAvailable.size);
            }
        }

        function selectSize(size) {
            selectedSize = size;
            document.getElementById('selectedSizeInput').value = size;
            document.getElementById('selectedSizeText').textContent = size;

            document.querySelectorAll('.size-btn').forEach(btn => {
                btn.classList.remove('ring', 'ring-offset-2', 'ring-primary-500');
                if (btn.dataset.size === size) {
                    btn.classList.add('ring', 'ring-offset-2', 'ring-primary-500');
                }
            });

            const selected = variants.find(v => v.color === selectedColor && v.size === size);
            if (selected) {
                document.getElementById('price').textContent = 'Rp' + selected.price.toLocaleString('id-ID');
                document.getElementById('stock').textContent = selected.stock;
            }
            updateTotalPrice();
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateTotalPrice();
        });
    </script>
    <script>
        const toggleBtn = document.getElementById('toggleDescription');
        const descriptionBox = document.getElementById('descriptionBox');
        const fadeOverlay = document.getElementById('fadeOverlay');
        const descriptionContent = document.getElementById('descriptionContent');

        let expanded = false;

        toggleBtn.addEventListener('click', () => {
            expanded = !expanded;
            if (expanded) {
                descriptionBox.classList.remove('max-h-40', 'overflow-hidden');
                fadeOverlay.classList.add('hidden');
                toggleBtn.textContent = 'Show less';
            } else {
                descriptionBox.classList.add('max-h-40', 'overflow-hidden');
                fadeOverlay.classList.remove('hidden');
                toggleBtn.textContent = 'Show more';
            }
        });

        window.addEventListener('DOMContentLoaded', () => {
            const textLength = descriptionContent.innerText.trim().length;
            const boxIsOverflowing = descriptionBox.scrollHeight > descriptionBox.clientHeight;

            // Kalau isinya pendek banget, jangan tampilkan show more
            if (textLength < 150 || !boxIsOverflowing) {
                toggleBtn.classList.add('hidden');
                fadeOverlay.classList.add('hidden');
            } else {
                fadeOverlay.classList.remove('hidden');
            }
        });
    </script>
</x-layouts.layout>
