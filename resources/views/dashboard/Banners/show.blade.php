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
        <!-- Back to Banners, Edit, and Delete Buttons -->
        <div class="mb-4 flex justify-between items-center">
            <a href="{{ route('banners.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Back to Banners</a>
            <div class="flex space-x-2">
                <a href="{{ route('banners.edit', $banner->id) }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Edit</a>
                <form action="/dashboard/banners/{{ $banner->id }}" method="POST" id="deleteForm-{{ $banner->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete('{{ $banner->id }}')"
                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Banner Details -->
        <div class="flex flex-col md:flex-row md:space-x-6">
            <!-- Banner Image -->
            <div class="mb-4 md:mb-0 md:w-1/3">
                @if ($banner->image)
                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->image }}"
                        class="w-full h-auto rounded-lg shadow-md">
                @else
                    <p class="text-gray-500">No image available.</p>
                @endif
            </div>

            <!-- Banner Information -->
            <div class="md:w-2/3">
                <!-- Banner -->
                <h2 class="text-gray-600 mb-4"><strong>Status:</strong> {{ $banner->status }}</h2>
            </div>
        </div>
    </div>
</x-dashboard.layout>
