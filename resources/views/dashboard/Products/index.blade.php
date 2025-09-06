<x-dashboard.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
        <div class="relative overflow-hidden bg-white shadow-md sm:rounded-lg">
            <!-- Notification Success -->
            @if (session('Success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: "{{ session('Success') }}",
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            position: 'bottom-end'
                        });
                    });
                </script>
            @endif
            <!-- Notification Error -->
            @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: "{{ $errors->first('name') }}", // Menampilkan pesan error pertama dari validasi
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            position: 'bottom-end'
                        });
                    });
                </script>
            @endif
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
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    {{-- SEARCH --}}
                    <form method="GET" action="{{ route('products.index') }}" class="w-full md:w-1/2">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817
                                    4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="bg-gray-50 border border-gray-300 text-sm text-gray-900 rounded-lg block w-full pl-10 p-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Search products" autocomplete="off">
                        </div>
                    </form>
                </div>
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <a href="/dashboard/products/create"
                        class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Add Product
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                            <th scope="col" class="px-4 py-3">No.</th>
                            <th scope="col" class="px-4 py-3">Image</th>
                            <th scope="col" class="px-4 py-3">Name</th>
                            <th scope="col" class="px-4 py-3">Slug</th>
                            <th scope="col" class="px-4 py-3">Color</th>
                            <th scope="col" class="px-4 py-3">Size</th>
                            <th scope="col" class="px-4 py-3">Variant Image</th>
                            <th scope="col" class="px-4 py-3">Price</th>
                            <th scope="col" class="px-4 py-3">Stock</th>
                            <th scope="col" class="px-4 py-3">Weight (g)</th>
                            <th scope="col" class="px-4 py-3">Description</th>
                            <th scope="col" class="px-4 py-3">Avarage Rating</th>
                            <th scope="col" class="px-4 py-3">Total Ratings</th>
                            <th scope="col" class="px-4 py-3">Sold</th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">Last Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($products as $product)
                            @foreach ($product->variants as $variant)
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="w-4 px-4 py-3 items-center justify-end relative" x-data="{ open: false, dropdownPosition: 'bottom' }"
                                        @click.away="open = false" x-init="() => {
                                            $watch('open', value => {
                                                if (value) {
                                                    const rect = $el.getBoundingClientRect();
                                                    const windowHeight = window.innerHeight;
                                        
                                                    // Cek ruang di bawah elemen
                                                    if (rect.bottom + 250 > windowHeight) {
                                                        dropdownPosition = 'top'; // Posisi dropdown di atas jika ruang terbatas di bawah
                                                    } else {
                                                        dropdownPosition = 'bottom'; // Jika ada cukup ruang, tampilkan di bawah
                                                    }
                                                }
                                            });
                                        }">
                                        <button @click="open = !open"
                                            class="inline-flex items-center text-sm font-medium hover:bg-gray-100 p-1.5 text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" x-transition
                                            :class="{ 'top-full': dropdownPosition === 'bottom', 'bottom-full mb-2': dropdownPosition === 'top' }"
                                            class="z-50 w-44 bg-white rounded divide-y divide-gray-100 shadow absolute left-0">
                                            <ul class="py-1 text-sm"
                                                aria-labelledby="dropdown-button-{{ $product->name }}">
                                                <li>
                                                    <a href="/dashboard/products/{{ $product->slug }}/edit"
                                                        class="flex w-full items-center py-2 px-4 hover:bg-gray-100 text-gray-700">
                                                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path
                                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="/dashboard/products/{{ $product->slug }}"
                                                        class="flex w-full items-center py-2 px-4 hover:bg-gray-100 text-gray-700">
                                                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                        </svg>
                                                        Preview
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="/dashboard/products/{{ $product->slug }}"
                                                        method="POST" id="deleteForm-{{ $product->slug }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            onclick="confirmDelete('{{ $product->slug }}')"
                                                            class="flex w-full items-center py-2 px-4 hover:bg-gray-100 text-red-500">
                                                            <svg class="w-4 h-4 mr-2" aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $no++ }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap relative z-10">
                                        <div x-data="{ open: false }" class="relative group">
                                            {{-- Gambar kecil --}}
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="Variant Image"
                                                class="w-12 h-12 object-cover rounded shadow cursor-pointer"
                                                @mouseenter="open = true" @mouseleave="open = false">

                                            {{-- Tooltip Preview Fullsize (di luar tabel, absolute ke window) --}}
                                            <div x-show="open" x-transition
                                                class="fixed z-50 bottom-20 left-1/2 transform -translate-x-1/2 w-[300px] border border-gray-300 rounded-lg shadow-lg bg-white p-1"
                                                @mouseenter="open = true" @mouseleave="open = false">
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="Preview"
                                                    class="w-full h-auto rounded">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap"
                                        title="{{ $product->name }}">
                                        {{ Str::limit($product->name, 25) }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap"
                                        title="{{ $product->slug }}">
                                        {{ Str::limit($product->slug, 30) }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $variant->color }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $variant->size }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap relative z-10">
                                        <div x-data="{ open: false }" class="relative group">
                                            {{-- Gambar kecil --}}
                                            <img src="{{ asset('storage/' . $variant->variant_image) }}"
                                                alt="Variant Image"
                                                class="w-12 h-12 object-cover rounded shadow cursor-pointer"
                                                @mouseenter="open = true" @mouseleave="open = false">

                                            {{-- Tooltip Preview Fullsize (di luar tabel, absolute ke window) --}}
                                            <div x-show="open" x-transition
                                                class="fixed z-50 bottom-20 left-1/2 transform -translate-x-1/2 w-[300px] border border-gray-300 rounded-lg shadow-lg bg-white p-1"
                                                @mouseenter="open = true" @mouseleave="open = false">
                                                <img src="{{ asset('storage/' . $variant->variant_image) }}"
                                                    alt="Preview" class="w-full h-auto rounded">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        Rp{{ number_format($variant->price) }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $variant->stock }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $variant->weight }}</td>
                                    <td
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"title="{{ $product->description }}">
                                        {!! Str::limit($product->description, 30) !!}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ number_format($product->average_rating, 1) ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $product->total_ratings ?? 0 }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $product->sold }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $product->updated_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard.layout>
