<x-layouts.layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <!-- Notification Success -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'bottom-end'
                });
            });
        </script>
    @endif

    <!-- Notification Info -->
    @if (session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Heads up!',
                    text: "{{ session('info') }}",
                    showConfirmButton: true,
                    position: 'mid'
                });
            });
        </script>
    @endif

    <div class="max-w-screen-md w-full px-4 mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <!-- Back to Dashboard Button -->
        <div class="mb-4">
            <a href="/user"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-lg hover:bg-gray-300 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                ← Back to Home
            </a>
        </div>

        <!-- Page Title -->
        <h2 class="text-2xl font-semibold mb-6 text-center">User Profile</h2>

        <!-- Profile Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full text-left border-collapse border border-gray-300 dark:border-gray-700">
                <tbody>
                    <tr class="border-b dark:border-gray-700">
                        <th class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-300">Name</th>
                        <td class="px-4 py-2">{{ auth()->user()->name }}</td>
                    </tr>
                    <tr class="border-b dark:border-gray-700">
                        <th class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-300">Username</th>
                        <td class="px-4 py-2">{{ auth()->user()->username }}</td>
                    </tr>
                    <tr class="border-b dark:border-gray-700">
                        <th class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-300">Email</th>
                        <td class="px-4 py-2">{{ auth()->user()->email }}</td>
                    </tr>
                    <tr class="border-b dark:border-gray-700">
                        <th class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-300">Password</th>
                        <td class="px-4 py-2">{{ str_repeat('*', min(strlen(auth()->user()->password), 15)) }}</td>
                    </tr>
                    <tr class="border-b dark:border-gray-700">
                        <th class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-300">Phone Number</th>
                        <td class="px-4 py-2">{{ auth()->user()->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-300">Address</th>
                        <td class="px-4 py-2">{{ auth()->user()->address ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-4 mt-6 justify-center">
            <a href="{{ route('profile.edit', ['user' => auth()->user()->id]) }}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Edit Profile
            </a>

            <form action="{{ route('profile.destroy', ['user' => auth()->user()->id]) }}" method="POST"
                id="deleteForm-{{ auth()->user()->id }}">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete('{{ auth()->user()->id }}')"
                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                    Delete Account
                </button>
            </form>
        </div>
    </div>

    <!-- Notification Delete Confirmation -->
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
