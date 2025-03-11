<x-dashboard.layout>
    <x-slot:title>Shipped Orders</x-slot:title>

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
            event.preventDefault();
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
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
        <div class="relative overflow-hidden bg-white shadow-md sm:rounded-lg">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <form method="GET" action="{{ route('dashboard.shippeds.index') }}" id="search-form"
                        class="space-y-3">
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

                <div class="w-full md:w-1/4 ml-auto">
                    <form method="GET" action="{{ route('dashboard.shippeds.index') }}" id="status-form">
                        <div>
                            <select name="status" id="status" onchange="this.form.submit();"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2 w-full">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status
                                </option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>
                                    Complete</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3">No.</th>
                            <th scope="col" class="px-4 py-3">Order Number</th>
                            <th scope="col" class="px-4 py-3">User Name</th>
                            <th scope="col" class="px-4 py-3">Products</th>
                            <th scope="col" class="px-4 py-3">Shipping Method</th>
                            <th scope="col" class="px-4 py-3">Order Status</th>
                            <th scope="col" class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            @if ($order->payment_status === 'approved')
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $order->cart_id }}</td>
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $order->user->name }}</td>
                                    <td class="px-4 py-3 text-gray-900 font-medium">
                                        <div class="p-2 rounded-lg bg-gray-50">
                                            @foreach ($order->items as $item)
                                                <div class="flex justify-between items-center py-1">
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="font-medium text-gray-900">{{ $item->product->name }}</span>
                                                        <span class="text-sm text-gray-500">
                                                            {{ $item->quantity }} pcs •
                                                            {{ $item->color ?? 'N/A' }} •
                                                            {{ $item->sizes->name ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @unless ($loop->last)
                                                    <hr class="border-gray-200 my-1">
                                                @endunless
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 font-medium">
                                        {{ $order->shipping_method ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 font-medium">
                                        @if ($order->order_status === 'pending')
                                            <span class="text-yellow-500">Pending</span>
                                        @elseif ($order->order_status === 'complete')
                                            <span class="text-green-500">Complete</span>
                                        @else
                                            <span class="text-gray-500">Unknown</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if ($order->order_status === 'pending')
                                            <form action="{{ route('dashboard.shippeds.complete', $order->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="bg-green-500 text-white px-2 py-1 rounded-lg hover:bg-green-600">
                                                    Mark as Complete
                                                </button>
                                            </form>
                                        @elseif ($order->order_status === 'complete')
                                            <form action="{{ route('dashboard.shippeds.delete', $order->id) }}"
                                                method="POST" id="delete-form-{{ $order->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    onclick="confirmDelete(event, 'delete-form-{{ $order->id }}')"
                                                    class="bg-red-700 text-white px-2 py-1 rounded-lg hover:bg-red-800">
                                                    Delete
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500">No Action</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard.layout>
