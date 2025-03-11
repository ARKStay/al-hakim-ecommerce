<x-dashboard.layout>
    <x-slot:title>Payments</x-slot:title>
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 5000,
                    toast: true,
                    position: 'bottom-end'
                });
            });
        </script>
    @endif

    <script>
        function confirmDelete(event, formId) {
            event.preventDefault(); // Mencegah form terkirim secara langsung
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this order?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit(); // Kirim form jika pengguna menekan "Yes"
                }
            });
        }
    </script>

    <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
        <div class="relative overflow-hidden bg-white shadow-md sm:rounded-lg">
            <div x-data="{ showModal: false, imageUrl: '' }">
                <!-- Search Form -->
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <form method="GET" action="{{ route('dashboard.payments') }}" id="search-form" class="space-y-3">
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2"
                                    placeholder="Search Order Number, User Name, or Status" autocomplete="off">
                            </div>
                        </form>
                    </div>

                    <!-- Dropdown untuk Status (Pindahkan ke kanan) -->
                    <div class="w-full md:w-1/4 ml-auto">
                        <form method="GET" action="{{ route('dashboard.payments') }}" id="search-form">
                            <div>
                                <select name="status" id="status" onchange="this.form.submit();"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2 w-full">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status
                                    </option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                        Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3"></th>
                                <th scope="col" class="px-4 py-3">No.</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Payment Proof</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Order Number</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">User Name</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Total Shopping</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Shipping Cost</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Total Payment</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Payment Status</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Order Date</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Last Update</th>
                                <th scope="col" class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="px-4 py-3 font-medium text-gray-900"></td>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        @if ($order->image)
                                            <img src="{{ asset('storage/' . $order->image) }}" alt="Payment Proof"
                                                class="w-20 h-20 object-cover cursor-pointer"
                                                @click="showModal = true; imageUrl = '{{ asset('storage/' . $order->image) }}'">
                                        @else
                                            No proof
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $order->cart_id }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $order->user->name }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">Rp
                                        {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">Rp
                                        {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 font-medium text-red-500 whitespace-nowrap">Rp
                                        {{ number_format($order->total_price + $order->shipping_cost, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 font-medium whitespace-nowrap">
                                        @if ($order->payment_status === 'pending')
                                            <span class="text-yellow-500">Pending</span>
                                        @elseif ($order->payment_status === 'approved')
                                            <span class="text-green-500">Approved</span>
                                        @elseif ($order->payment_status === 'rejected')
                                            <span class="text-red-500">Rejected</span>
                                        @else
                                            <span class="text-gray-500">Unknown</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $order->updated_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 font-medium whitespace-nowrap">
                                        <div class="flex flex-col space-y-2">
                                            @if ($order->payment_status === 'pending')
                                                <form action="{{ route('dashboard.payments.approve', $order->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="w-full bg-green-500 text-white px-2 py-1 rounded-lg hover:bg-green-600">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('dashboard.payments.reject', $order->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="w-full bg-red-500 text-white px-2 py-1 rounded-lg hover:bg-red-600">
                                                        Reject
                                                    </button>
                                                </form>
                                            @elseif ($order->payment_status === 'rejected')
                                                <form action="{{ route('dashboard.payments.delete', $order->id) }}"
                                                    method="POST" id="delete-form-{{ $order->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        onclick="confirmDelete(event, 'delete-form-{{ $order->id }}')"
                                                        class="w-full bg-red-700 text-white px-2 py-1 rounded-lg hover:bg-red-800">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal View -->
                <div x-show="showModal" x-cloak
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                    @click.self="showModal = false">
                    <div class="relative bg-white rounded-lg shadow-lg p-6 max-w-lg">
                        <button @click="showModal = false"
                            class="absolute -top-3 -right-3 bg-gray-100 text-gray-500 hover:text-gray-800 rounded-full p-1 shadow-md focus:outline-none focus:ring-2 focus:ring-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <img :src="imageUrl" alt="Payment Proof" class="w-full h-auto rounded-lg">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.layout>
