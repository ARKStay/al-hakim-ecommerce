<x-layouts.layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    {{-- SweetAlert Notifications --}}
    @if (session('success') || session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: "{{ session('success') ? 'success' : 'info' }}",
                    title: "{{ session('success') ? 'Success!' : 'Heads up!' }}",
                    text: "{{ session('success') ?? session('info') }}",
                    showConfirmButton: {{ session('success') ? 'false' : 'true' }},
                    timer: {{ session('success') ? '3000' : 'null' }},
                    toast: {{ session('success') ? 'true' : 'false' }},
                    position: {{ session('success') ? "'bottom-end'" : "'center'" }},
                });
            });
        </script>
    @endif

    <div class="max-w-screen-md w-full px-4 mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">

        {{-- Breadcrumb --}}
        <nav class="text-sm text-gray-500 mb-6 md:mt-2" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li>
                    <a href="/user" class="text-blue-500 hover:underline">Home</a>
                    <span class="mx-2">/</span>
                </li>
                <li class="text-gray-700 dark:text-gray-300">Profile</li>
            </ol>
        </nav>

        {{-- Title & Actions --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <h2 class="text-2xl font-semibold text-center md:text-left mb-4 md:mb-0">My Profile</h2>
            <div class="flex gap-4">
                <a href="{{ route('profile.edit', ['user' => auth()->user()->id]) }}"
                    class="bg-blue-500 text-white text-sm px-4 py-2 rounded hover:bg-blue-600 transition">
                    Edit Profile
                </a>

                <form action="{{ route('profile.destroy', ['user' => auth()->user()->id]) }}" method="POST"
                    id="deleteForm-{{ auth()->user()->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete('{{ auth()->user()->id }}')"
                        class="bg-red-500 text-white text-sm px-4 py-2 rounded hover:bg-red-600 transition">
                        Delete Account
                    </button>
                </form>
            </div>
        </div>

        {{-- Profile Info Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow">
                <p class="text-sm text-gray-500">Name</p>
                <p class="text-base font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow">
                <p class="text-sm text-gray-500">Username</p>
                <p class="text-base font-medium text-gray-900 dark:text-white">{{ auth()->user()->username }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow">
                <p class="text-sm text-gray-500">Email</p>
                <p class="text-base font-medium text-gray-900 dark:text-white">{{ auth()->user()->email }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow">
                <p class="text-sm text-gray-500">Password</p>
                <p class="text-base font-medium text-gray-900 dark:text-white">
                    {{ str_repeat('*', min(strlen(auth()->user()->password), 15)) }}
                </p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow">
                <p class="text-sm text-gray-500">Phone Number</p>
                <p class="text-base font-medium text-gray-900 dark:text-white">{{ auth()->user()->phone ?? '-' }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow">
                <p class="text-sm text-gray-500">Address</p>
                <p class="text-base font-medium text-gray-900 dark:text-white">{{ auth()->user()->address ?? '-' }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow sm:col-span-2">
                <p class="text-sm text-gray-500">Destination</p>
                <p class="text-base font-medium text-gray-900 dark:text-white">
                    {{ auth()->user()->district_name ?? '-' }},
                    {{ auth()->user()->city_name ?? '-' }},
                    {{ auth()->user()->province_name ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Confirm Delete Script --}}
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
</x-layouts.layout>
