<x-dashboard.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
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
    <!-- Notification Deactivate -->
    <script>
        function confirmDeactivate(id) {
            Swal.fire({
                title: 'Deactivate this account?',
                text: "The user will not be able to log in until reactivated.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, deactivate it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deactivateForm-' + id).submit();
                }
            });
        }
    </script>
    <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
        <div class="relative overflow-hidden bg-white shadow-md sm:rounded-lg">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <form class="flex items-center">
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor"
                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2"
                                placeholder="Search" autocomplete="off">
                        </div>
                        <button type="submit"
                            class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Search
                        </button>
                    </form>
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
                            <th scope="col" class="px-4 py-3">Name</th>
                            <th scope="col" class="px-4 py-3">Username</th>
                            <th scope="col" class="px-4 py-3">Email</th>
                            <th scope="col" class="px-4 py-3">Phone</th>
                            <th scope="col" class="px-4 py-3">Address</th>
                            <th scope="col" class="px-4 py-3">Password</th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">Account Status</th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">Account Created</th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">Last Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b hover:bg-gray-100">
                                <td class="w-4 px-4 py-3 items-center justify-end relative" x-data="{ open: false, dropdownPosition: 'bottom' }"
                                    @click.away="open = false" x-init="() => {
                                        $watch('open', value => {
                                            if (value) {
                                                const rect = $el.getBoundingClientRect();
                                                const windowHeight = window.innerHeight;
                                    
                                                // Check available space below the element
                                                if (rect.bottom + 250 > windowHeight) {
                                                    dropdownPosition = 'top';
                                                } else {
                                                    dropdownPosition = 'bottom';
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

                                        <ul class="py-1 text-sm">

                                            {{-- Admin → Edit --}}
                                            @if ($user->role === 'admin')
                                                <li>
                                                    <a href="{{ url('/dashboard/users/' . $user->id . '/edit') }}"
                                                        class="flex w-full items-center py-2 px-4 hover:bg-gray-100 text-blue-500">
                                                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.586-9.414a2 2 0 0 1 2.828 2.828L12.828 15H9v-3.828l8.414-8.414z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- User → Deactivate Account --}}
                                            @if ($user->role === 'user')
                                                <li>
                                                    <form
                                                        action="{{ url('/dashboard/users/' . $user->id . ($user->account_status === 'active' ? '/deactivate' : '/activate')) }}"
                                                        method="POST" id="statusForm-{{ $user->id }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="button"
                                                            onclick="confirmStatusChange('{{ $user->id }}', '{{ $user->account_status }}')"
                                                            class="flex w-full items-center py-2 px-4 hover:bg-gray-100 {{ $user->account_status === 'active' ? 'text-orange-500' : 'text-green-500' }}">
                                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                                stroke-width="2">
                                                                @if ($user->account_status === 'active')
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M18.364 5.636l-12.728 12.728m12.728 0L5.636 5.636" />
                                                                @else
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M5 13l4 4L19 7" />
                                                                @endif
                                                            </svg>
                                                            {{ $user->account_status === 'active' ? 'Deactivate Account' : 'Activate Account' }}
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap"
                                    title="{{ $user->name }}">
                                    {{ Str::limit($user->name, 20) }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap"
                                    title="{{ $user->username }}">
                                    {{ Str::limit($user->username, 20) }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap"
                                    title="{{ $user->email }}">
                                    {{ Str::limit($user->email, 20) }}
                                </td>
                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                                    title="{{ $user['phone'] }}">
                                    {{ Str::limit($user['phone'], 20) }}</td>
                                <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                                    title="{{ $user['address'] }}">
                                    {{ Str::limit($user['address'], 20) }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ str_repeat('*', min(10, strlen($user->password))) }}</td>
                                <td
                                    class="px-4 py-3 font-medium 
                                    {{ $user->account_status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ucfirst($user->account_status) }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $user->created_at->format('d M Y, h:i A') }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $user->updated_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function confirmStatusChange(id, status) {
            let actionText = status === 'active' ? 'Deactivate this account?' : 'Activate this account?';
            let confirmButtonText = status === 'active' ? 'Yes, deactivate it!' : 'Yes, activate it!';
            let confirmButtonColor = status === 'active' ? '#f97316' : '#22c55e';

            Swal.fire({
                title: actionText,
                text: status === 'active' ?
                    "The user will not be able to log in until reactivated." :
                    "The user will regain access to their account.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#3085d6',
                confirmButtonText: confirmButtonText
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('statusForm-' + id).submit();
                }
            });
        }
    </script>
</x-dashboard.layout>
