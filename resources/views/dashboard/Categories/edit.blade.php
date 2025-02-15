<x-dashboard.layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <!-- Form -->
        <form action="/dashboard/categories/{{ $category->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                    class="mt-1 block w-96 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    required autocomplete="off">
                @error('name')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Update Category
                </button>
                <a href="{{ route('categories.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Back to Table
                </a>
            </div>
        </form>
    </div>
</x-dashboard.layout>
