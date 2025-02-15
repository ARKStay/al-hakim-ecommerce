<x-dashboard.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <!-- Notification Delete -->
        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm-' + id).submit();
                    }
                });
            }
        </script>
        <!-- Back to Products, Edit, and Delete Buttons -->
        <div class="mb-4 flex justify-between items-center">
            <a href="{{ route('products.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Back to Products</a>
            <div class="flex space-x-2">
                <a href="{{ route('products.edit', $product->slug) }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Edit</a>
                <form action="/dashboard/products/{{ $product->slug }}" method="POST"
                    id="deleteForm-{{ $product->slug }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete('{{ $product->slug }}')"
                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Product Details -->
        <div class="flex flex-col md:flex-row md:space-x-6">
            <!-- Product Image -->
            <div class="mb-4 md:mb-0 md:w-1/3 flex items-center justify-center bg-gray-100 rounded-lg h-48">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-auto rounded-lg shadow-md">
                @else
                    <div class="flex items-center justify-center w-full h-full">
                        <p class="text-gray-500 text-center">No image available</p>
                    </div>
                @endif
            </div>

            <!-- Product Information -->
            <div class="md:w-2/3">
                <!-- Product Name -->
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h2>

                <!-- Product Category -->
                <p class="text-gray-600 mb-4"><strong>Category:</strong> {{ $product->category->name }}</p>

                <!-- Product Price -->
                <p class="text-gray-600 mb-4"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>

                <!-- Product Stock -->
                <p class="text-gray-600 mb-4"><strong>Stock:</strong> {{ $product->stock }}</p>

                <!-- Product Size -->
                <p class="text-gray-600 mb-4"><strong>Size:</strong> {{ $product->size->name ?? 'N/A' }}</p>

                <!-- Product Color -->
                <p class="text-gray-600 mb-4"><strong>Color:</strong> {{ $product->color }}</p>

                <!-- Product Description -->
                <p class="text-gray-600 mb-4"><strong>Description:</strong> {!! $product->description ?? 'No description available.' !!}</p>

                <!-- Average Rating and Total Ratings -->
                <div class="mb-4">
                    <p class="text-gray-600"><strong>Average Rating:</strong>
                        {{ number_format($average_rating, 1) ?? 'No ratings yet' }} / 5
                    </p>
                    <p class="text-gray-600"><strong>Total Ratings:</strong> {{ $total_ratings ?? 0 }}</p>
                </div>

                <!-- User Comments -->
                <div class="mt-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">User Reviews</h3>
                    @forelse ($product->ratings as $rating)
                        <div class="mb-4">
                            <div class="flex items-center mb-2">
                                <p class="font-medium">{{ $rating->user->name }}</p>
                                <!-- Display the comment's date -->
                                <p class="text-gray-500 text-sm ml-2">{{ $rating->created_at->diffForHumans() }}</p>
                            </div>
                            <p class="text-gray-600">{{ $rating->comment ?? 'No comment' }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">No reviews yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-dashboard.layout>
