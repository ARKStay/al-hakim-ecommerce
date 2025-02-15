<x-dashboard.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <!-- Form -->
        <form action="/dashboard/banners/{{ $banner->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Product Image -->
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image">Upload
                    Image</label>
                <input type="hidden" name="oldImage" value="{{ $banner->image }}" < class="flex items-start">
                @if ($banner->image)
                    <img src="{{ asset('storage/' . $banner->image) }}"
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

            <!-- Status Input -->
            <div class="mb-4">
                <label for="status"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                <select name="status" id="status"
                    class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    <option value="on" {{ $banner->status == 'on' ? 'selected' : '' }}>On</option>
                    <option value="off" {{ $banner->status == 'off' ? 'selected' : '' }}>Off</option>
                </select>
                @error('status')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Update Banner
                </button>
                <a href="{{ route('banners.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Back to Table
                </a>
            </div>
        </form>
    </div>
    <script>
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
