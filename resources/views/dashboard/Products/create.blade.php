<x-dashboard.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <form id="productForm" action="/dashboard/products" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <!-- Product Name -->
                <div class="mb-4">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="input"
                        placeholder="Enter product name" required autocomplete="off" autofocus>
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Slug -->
                <div class="mb-4">
                    <label for="slug" class="block mb-2 text-sm font-medium text-gray-900">Product Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="input"
                        placeholder="Enter product slug" required readonly>
                    @error('slug')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Product Image -->
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900" for="image">Upload Image</label>
                <div class="flex items-start">
                    <img class="img-preview w-40 h-auto hidden rounded-lg shadow-md mb-3" alt="Image Preview">
                </div>
                <input class="input-file" id="image" type="file" name="image" onchange="previewImage()">
                <p class="text-sm text-gray-500">SVG, PNG, JPG (MAX. 30MB).</p>
                @error('image')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Product Description -->
            <div class="mb-4">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Product
                    Description</label>
                <input id="description" type="hidden" name="description" value="{{ old('description') }}">
                <trix-editor input="description"></trix-editor>
            </div>

            <hr class="my-6">
            <h2 class="text-lg font-semibold mb-2">Product Variants Generator (Grouped by Color)</h2>

            <!-- Generator Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium">Colors (comma separated)</label>
                    <input type="text" id="variantColors" class="input" placeholder="e.g. Black, White">
                </div>
                <div>
                    <label class="block text-sm font-medium">Sizes (comma separated)</label>
                    <input type="text" id="variantSizes" class="input" placeholder="e.g. S, M, L">
                </div>
            </div>
            <button type="button" onclick="generateVariantsGrouped()"
                class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600 mb-6">
                🔄 Generate Variant Groups
            </button>

            <!-- Generated Variants -->
            <div id="variant-container"></div>

            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add
                    Product</button>
                <a href="{{ route('products.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back to Table</a>
            </div>
        </form>
    </div>

    <script>
        const name = document.querySelector('#name');
        const slug = document.querySelector('#slug');
        name.addEventListener('change', function() {
            fetch('/dashboard/products/checkSlug?name=' + name.value)
                .then(response => response.json())
                .then(data => slug.value = data.slug);
        });

        document.addEventListener('trix-file-accept', function(e) {
            e.preventDefault();
        });

        function previewImage() {
            const image = document.querySelector('#image');
            const imgPreview = document.querySelector('.img-preview');
            if (image.files && image.files[0]) {
                imgPreview.style.display = 'block';
                const reader = new FileReader();
                reader.onload = e => imgPreview.src = e.target.result;
                reader.readAsDataURL(image.files[0]);
            } else {
                imgPreview.style.display = 'none';
            }
        }

        function generateVariantsGrouped() {
            const colors = document.getElementById('variantColors').value.split(',').map(c => c.trim().toLowerCase())
                .filter(Boolean);
            const sizes = document.getElementById('variantSizes').value.split(',').map(s => s.trim().toUpperCase()).filter(
                Boolean);
            const container = document.getElementById('variant-container');
            let index = container.querySelectorAll('.variant-group').length;

            colors.forEach(color => {
                let colorGroup = container.querySelector(`[data-color="${color}"]`);

                if (!colorGroup) {
                    colorGroup = document.createElement('div');
                    colorGroup.setAttribute('data-color', color);
                    colorGroup.className = 'mb-6 border-2 border-indigo-500 p-4 rounded';
                    colorGroup.innerHTML = `
                        <div class="mb-4 relative">
                            <label class="block text-sm font-medium mb-1">Color: <strong>${color}</strong></label>
                            <input type="file" name="variants[${index}][variant_image]" class="input-file">
                            <button type="button" onclick="this.closest('[data-color]').remove()" class="absolute top-0 right-0 bg-red-600 text-white text-sm px-2 py-1">X</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 variants-inner"></div>
                    `;
                    container.appendChild(colorGroup);
                }

                const variantsInner = colorGroup.querySelector('.variants-inner');

                sizes.forEach(size => {
                    const exists = Array.from(variantsInner.querySelectorAll('input[name$="[size]"]'))
                        .some(input => input.value === size);

                    if (!exists) {
                        const group = document.createElement('div');
                        group.className = 'variant-group border border-gray-300 p-4 rounded-lg relative';
                        group.innerHTML = `
                            <input type="hidden" name="variants[${index}][color]" value="${color}">
                            <input type="hidden" name="variants[${index}][size]" value="${size}">
                            <label class="block text-sm font-medium">Size: ${size}</label>
                            <input type="number" name="variants[${index}][price]" placeholder="Price" class="input mb-2" required>
                            <input type="number" name="variants[${index}][stock]" placeholder="Stock" class="input mb-2" required>
                            <input type="number" step="0.01" name="variants[${index}][weight]" placeholder="Weight (g)" class="input" required>
                            <button type="button" onclick="this.parentElement.remove()" class="absolute top-1 right-1 bg-red-600 text-white px-1 text-xs">X</button>
                        `;
                        variantsInner.appendChild(group);
                        index++;
                    }
                });
            });
        }

        // 🛡️ Cek minimal 1 varian sebelum submit
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const container = document.getElementById('variant-container');
            const variantCount = container.querySelectorAll('.variant-group').length;

            if (variantCount < 1) {
                e.preventDefault();
                alert('Minimal harus ada satu varian produk yang ditambahkan!');
            }
        });
    </script>

    <style>
        .input {
            border: 1px solid #ccc;
            border-radius: 0.375rem;
            padding: 0.5rem;
            width: 100%;
        }

        .input-file {
            width: 100%;
            padding: 0.25rem;
        }
    </style>
</x-dashboard.layout>
