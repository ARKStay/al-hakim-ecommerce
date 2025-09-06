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
                <div class="flex justify-between items-center p-4">
                    <a href="{{ route('crm.create') }}"
                        class="flex items-center text-white bg-primary-700 hover:bg-primary-800 px-4 py-2 rounded-lg">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Add Promotion
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
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Promotion Name</th>
                            <th class="px-4 py-3">Message</th>
                            <th class="px-4 py-3">Product Link</th>
                            <th class="px-4 py-3">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($promotions as $promotion)
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
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div x-show="open" x-transition
                                        :class="{ 'top-full': dropdownPosition === 'bottom', 'bottom-full mb-2': dropdownPosition === 'top' }"
                                        class="z-50 w-44 bg-white rounded divide-y divide-gray-100 shadow absolute left-0">
                                        <ul class="py-1 text-sm" aria-labelledby="dropdown-button-{{ $promotion->id }}">
                                            <li>
                                                <form action="{{ route('crm.destroy', $promotion->id) }}" method="POST"
                                                    id="deleteForm-{{ $promotion->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        onclick="confirmDelete('{{ $promotion->id }}')"
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
                                <td class="px-4 py-2 font-medium text-gray-900">{{ $no++ }}</td>
                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                                    title="{{ $promotion->title }}">{{ Str::limit($promotion->title, 25) }}</td>
                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                                    title="{{ $promotion->message }}">{{ Str::limit($promotion->message, 25) }}</td>
                                <td class="px-4 py-2">
                                    @if ($promotion->product_link)
                                        <a href="{{ $promotion->product_link }}" target="_blank"
                                            class="text-blue-600 hover:underline">Lihat Produk</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-2 font-medium text-gray-900">{{ $promotion->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard.layout>
