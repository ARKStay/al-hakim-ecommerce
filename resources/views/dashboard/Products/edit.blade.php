<x-dashboard.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <!-- Form -->
        <form action="/dashboard/products/{{ $product->slug }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <!-- Product Name -->
                <div class="mb-4">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                        Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter product name" required autocomplete="off" autofocus>
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Slug -->
                <div class="mb-4">
                    <label for="slug" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                        Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter product slug" required autocomplete="off" readonly>
                    @error('slug')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Category -->
                <div class="mb-4">
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                        Category</label>
                    <select name="category_id" id="category"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select a category
                        </option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Size -->
                <div class="mb-4">
                    <label for="size" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                        Size</label>
                    <select name="sizes_id" id="size"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" disabled {{ old('sizes_id') ? '' : 'selected' }}>Select a size</option>
                        @foreach ($sizes as $size)
                            <option value="{{ $size->id }}"
                                {{ old('sizes_id', $product->sizes_id) == $size->id ? 'selected' : '' }}>
                                {{ $size->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('sizes_id')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Price -->
                <div class="mb-4">
                    <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                        Price</label>
                    <input type="number" name="price" id="price" min="0"
                        value="{{ old('price', $product->price) }}"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter product price" required>
                    @error('price')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Stock -->
                <div class="mb-4">
                    <label for="stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                        Stock</label>
                    <input type="number" name="stock" id="stock" min="0"
                        value="{{ old('stock', $product->stock) }}"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter product stock" required>
                    @error('stock')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Color -->
                <div class="mb-4">
                    <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                        Color</label>
                    <input type="text" name="color" id="color" value="{{ old('color', $product->color) }}"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter product color" required autocomplete="off" autofocus>
                    @error('color')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Product Image -->
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image">Upload
                    Image</label>
                <input type="hidden" name="oldImage" value="{{ $product->image }}" < class="flex items-start">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                        class="img-preview w-40 h-auto rounded-lg shadow-md mb-3" alt="Image Preview">
                @else
                    <p class="text-gray-500 mb-3">No image available.</p>
                @endif
                <input
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    aria-describedby="file_input_help" id="image" type="file" name="image"
                    onchange="previewImage()">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG (MAX. 30
                    MB).
                </p>
                @error('image')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Product Description -->
            <div class="mb-4">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                    Description</label>
                <input id="description" type="hidden" name="description"
                    value="{{ old('description', $product->description) }}">
                <trix-editor input="description"></trix-editor>
            </div>

            <!-- Buttons -->
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Update Product
                </button>
                <a href="{{ route('products.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Back to Table
                </a>
            </div>
        </form>
    </div>
    <script>
        const name = document.querySelector('#name');
        const slug = document.querySelector('#slug');
        name.addEventListener('change', function() {
            fetch('/dashboard/products/checkSlug?name=' + name.value)
                .then(response => response.json())
                .then(data => slug.value = data.slug)
        })

        document.addEventListener('trix-file-accept', function(e) {
            e.preventDefault();
        });

        function previewImage() {
            const image = document.querySelector('#image');
            let imgPreview = document.querySelector('.img-preview');

            if (!imgPreview) {
                // Jika elemen img-preview belum ada, buat elemen baru
                imgPreview = document.createElement('img');
                imgPreview.className = 'img-preview w-40 h-auto rounded-lg shadow-md mb-3';
                image.insertAdjacentElement('beforebegin', imgPreview);
            }

            if (image.files && image.files[0]) {
                imgPreview.style.display = 'block'; // Tampilkan gambar

                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(event) {
                    imgPreview.src = event.target.result; // Setel sumber gambar
                };
            } else {
                imgPreview.style.display = 'none'; // Sembunyikan gambar jika tidak ada file
            }
        }
    </script>
</x-dashboard.layout>
