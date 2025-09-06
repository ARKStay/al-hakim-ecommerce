<x-dashboard.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-semibold mb-4">Add New Promotion</h2>

        <form action="{{ route('crm.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Promotion Title --}}
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Promotion Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                    required>
                @error('title')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Promotion Message --}}
            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-gray-700">Promotion Message</label>
                <textarea id="message" name="message" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                    required>{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Product Link --}}
            <div class="mb-4">
                <label for="product_link" class="block text-sm font-medium text-gray-700">Product Link</label>
                <input type="url" id="product_link" name="product_link" value="{{ old('product_link') }}"
                    placeholder="https://example.com/product"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                @error('product_link')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end space-x-2">
                <a href="{{ route('crm.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">Save
                    & Send</button>
            </div>
        </form>
    </div>
</x-dashboard.layout>
