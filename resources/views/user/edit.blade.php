<x-layouts.layout>
    <x-slot:title>Edit Profile</x-slot:title>
    <div class="max-w-screen-md w-full px-4 mx-auto 2xl:px-0 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <!-- Back to Profile Button -->
        <div class="mb-4">
            <a href="/user/profile"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-lg hover:bg-gray-300 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                ← Back to Profile
            </a>
        </div>

        <!-- Page Title -->
        <h2 class="text-2xl font-semibold mb-6 text-center">Edit Profile</h2>

        <!-- Edit Profile Form -->
        <form id="editForm" action="{{ route('profile.update', ['user' => auth()->user()->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-gray-700 font-medium">Name:</label>
                    <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                        required
                        class="block w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300 focus:border-blue-500">
                </div>
                <!-- Username -->
                <div>
                    <label for="username" class="block text-gray-700 font-medium">Username:</label>
                    <input type="text" name="username" id="username"
                        value="{{ old('username', auth()->user()->username) }}" required
                        class="block w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300 focus:border-blue-500">
                </div>
                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-700 font-medium">Email:</label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email', auth()->user()->email) }}" required
                        class="block w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300 focus:border-blue-500">
                </div>
                <!-- Password -->
                <div>
                    <label for="password" class="block text-gray-700 font-medium">Password:</label>
                    <input type="password" name="password" id="password"
                        class="block w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300 focus:border-blue-500"
                        placeholder="Leave blank to keep current password">
                </div>
                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-gray-700 font-medium">Phone Number:</label>
                    <input type="text" name="phone" id="phone"
                        value="{{ old('phone', auth()->user()->phone) }}"
                        class="block w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300 focus:border-blue-500">
                </div>
                <!-- Address -->
                <div>
                    <label for="address" class="block text-gray-700 font-medium">Address:</label>
                    <textarea name="address" id="address" rows="3"
                        class="block w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300 focus:border-blue-500">{{ old('address', auth()->user()->address) }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 text-center">
                <button type="button" onclick="confirmEdit()"
                    class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        function confirmEdit() {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save these changes?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('editForm').submit();
                }
            });
        }
    </script>
</x-layouts.layout>
