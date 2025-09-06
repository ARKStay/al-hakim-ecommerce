<x-dashboard.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <form action="/dashboard/products/{{ $product->slug }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <!-- Product Name -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                        class="input" placeholder="Enter product name" required autocomplete="off" autofocus>
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block mb-2 text-sm font-medium text-gray-900">Product Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}"
                        class="input bg-gray-100" readonly>
                    @error('slug')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Product Image -->
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900" for="image">Main Product Image</label>
                <input type="hidden" name="oldImage" value="{{ $product->image }}">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                        class="img-preview w-40 h-auto rounded-lg shadow-md mb-3" alt="Image Preview">
                @endif
                <input class="input-file" id="image" type="file" name="image" onchange="previewImage()">
                <p class="mt-1 text-sm text-gray-500">JPG, PNG, SVG (MAX. 30 MB)</p>
                @error('image')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Product Description -->
            <div class="mb-6">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Product
                    Description</label>
                <input id="description" type="hidden" name="description"
                    value="{{ old('description', $product->description) }}">
                <trix-editor input="description"></trix-editor>
            </div>

            <hr class="my-6">
            <h2 class="text-lg font-semibold mb-4">Edit Product Variants</h2>

            <!-- Add New Variants -->
            <hr class="my-6">
            <h2 class="text-lg font-semibold mb-4">Add New Variant(s)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium">Colors (comma separated)</label>
                    <input type="text" id="editVariantColors" class="input" placeholder="e.g. blue, black">
                </div>
                <div>
                    <label class="block text-sm font-medium">Sizes (comma separated)</label>
                    <input type="text" id="editVariantSizes" class="input" placeholder="e.g. S, M, L">
                </div>
            </div>
            <button type="button" onclick="generateEditVariantsGrouped()"
                class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600 mb-6">
                Add Variant Group
            </button>
            <div id="edit-variant-container"></div>

            @php
                $grouped = $product->variants->groupBy('color');
                $colorIndex = 0;
            @endphp
            @foreach ($grouped as $color => $groupedVariants)
                <div class="color-group border rounded-md p-4 mb-6 bg-gray-50 relative"
                    data-edit-color="{{ $color }}">
                    <div class="flex justify-between items-center mb-3">
                        <div class="font-semibold">Color: <span class="capitalize">{{ $color }}</span></div>
                        <button type="button" onclick="this.closest('.color-group').remove()"
                            class="bg-red-600 text-white px-2 py-1 text-sm font-semibold rounded shadow">X</button>
                    </div>
                    <input type="hidden" name="colors[]" value="{{ $color }}">

                    <div class="mt-3 mb-4">
                        @if ($groupedVariants->first()->variant_image)
                        <img src="{{ asset('storage/' . $groupedVariants->first()->variant_image) }}"
                        alt="Variant Image" class="w-20 mt-2 rounded-lg shadow-md">
                        @endif
                        <input type="file" name="variant_images[{{ $color }}]" class="input-file mt-2">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 variants-inner">
                        @foreach ($groupedVariants as $variantIndex => $variant)
                            <div class="border p-3 rounded bg-white relative variant-group">
                                <input type="hidden" name="variants[{{ $color }}][{{ $variantIndex }}][id]"
                                    value="{{ $variant->id }}">
                                <input type="hidden" name="variants[{{ $color }}][{{ $variantIndex }}][color]"
                                    value="{{ $color }}">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium">Size</label>
                                        <input type="text"
                                            name="variants[{{ $color }}][{{ $variantIndex }}][size]"
                                            value="{{ $variant->size }}" class="input">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Price</label>
                                        <input type="number"
                                            name="variants[{{ $color }}][{{ $variantIndex }}][price]"
                                            value="{{ $variant->price }}" class="input">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Stock</label>
                                        <input type="number"
                                            name="variants[{{ $color }}][{{ $variantIndex }}][stock]"
                                            value="{{ $variant->stock }}" class="input">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Weight (g)</label>
                                        <input type="number" step="0.01"
                                            name="variants[{{ $color }}][{{ $variantIndex }}][weight]"
                                            value="{{ $variant->weight }}" class="input">
                                    </div>
                                </div>

                                <button type="button" onclick="this.closest('.variant-group').remove()"
                                    class="absolute top-2 right-2 bg-red-600 text-white w-5 h-5 text-sm font-bold">X</button>
                            </div>
                        @endforeach
                    </div>
                </div>
                @php $colorIndex++; @endphp
            @endforeach

            <!-- Buttons -->
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Update
                    Product</button>
                <a href="{{ route('products.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Back to Table</a>
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
            let imgPreview = document.querySelector('.img-preview');
            if (!imgPreview) {
                imgPreview = document.createElement('img');
                imgPreview.className = 'img-preview w-40 h-auto rounded-lg shadow-md mb-3';
                image.insertAdjacentElement('beforebegin', imgPreview);
            }
            if (image.files && image.files[0]) {
                imgPreview.style.display = 'block';
                const reader = new FileReader();
                reader.onload = function(event) {
                    imgPreview.src = event.target.result;
                };
                reader.readAsDataURL(image.files[0]);
            } else {
                imgPreview.style.display = 'none';
            }
        }

        function generateEditVariantsGrouped() {
            const colors = document.getElementById('editVariantColors').value.split(',').map(c => c.trim().toLowerCase())
                .filter(Boolean);
            const sizes = document.getElementById('editVariantSizes').value.split(',').map(s => s.trim().toUpperCase())
                .filter(Boolean);
            const container = document.getElementById('edit-variant-container');
            let index = document.querySelectorAll('.variant-group').length;

            colors.forEach(color => {
                let colorGroup = document.querySelector(`[data-edit-color="${color}"]`);
                if (!colorGroup) {
                    colorGroup = document.createElement('div');
                    colorGroup.setAttribute('data-edit-color', color);
                    colorGroup.className = 'mb-6 border rounded-md p-4 bg-gray-50 relative';
                    colorGroup.innerHTML = `
                        <div class="flex justify-between items-center mb-3">
                            <div class="font-semibold">Color: <span class="capitalize">${color}</span></div>
                            <button type="button" onclick="this.closest('[data-edit-color]').remove()" class="bg-red-600 text-white px-2 py-1 text-sm font-semibold rounded shadow">X</button>
                        </div>
                        <input type="hidden" name="colors[]" value="${color}">
                        <div class="mb-4">
                            <input type="file" name="variant_images[${color}]" class="input-file">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 variants-inner"></div>
                    `;
                    container.appendChild(colorGroup);
                }

                const variantsInner = colorGroup.querySelector('.variants-inner');
                sizes.forEach(size => {
                    const exists = Array.from(variantsInner.querySelectorAll('input[name$="[size]"]')).some(
                        input => input.value === size);
                    if (!exists) {
                        const group = document.createElement('div');
                        group.className = 'variant-group border p-3 rounded bg-white relative';
                        group.innerHTML = `
                            <input type="hidden" name="variants[${color}][${index}][color]" value="${color}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium">Size</label>
                                    <input type="text" name="variants[${color}][${index}][size]" value="${size}" class="input" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Price</label>
                                    <input type="number" name="variants[${color}][${index}][price]" class="input" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Stock</label>
                                    <input type="number" name="variants[${color}][${index}][stock]" class="input" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Weight (kg)</label>
                                    <input type="number" step="0.01" name="variants[${color}][${index}][weight]" class="input" required>
                                </div>
                            </div>
                            <button type="button" onclick="this.closest('.variant-group').remove()" class="absolute top-2 right-2 bg-red-600 text-white w-5 h-5 text-sm font-bold">X</button>
                        `;
                        variantsInner.appendChild(group);
                        index++;
                    }
                });
            });
        }
    </script>

    <style>
        .input {
            border: 1px solid #ccc;
            border-radius: 0.375rem;
            padding: 0.5rem;
            width: 100%;
        }

        .input-file {
            border: 1px solid #ccc;
            border-radius: 0.375rem;
            padding: 0.25rem;
            width: 100%;
            background-color: #f9fafb;
        }

        .color-group label {
            font-weight: 500;
        }
    </style>
</x-dashboard.layout>
