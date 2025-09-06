<x-dashboard.layout>
    <x-slot:title>Shipped Orders</x-slot:title>

    {{-- SUCCESS NOTIFICATION --}}
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

    {{-- DELETE CONFIRMATION --}}
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

    {{-- MAIN CONTAINER --}}
    <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
        <div class="relative overflow-x-auto bg-white shadow-md sm:rounded-lg">
            {{-- FILTER BAR --}}
            <div class="flex flex-col md:flex-row items-center justify-between p-4 gap-4">
                {{-- SEARCH --}}
                <form method="GET" action="{{ route('dashboard.shippeds.index') }}" class="w-full md:w-1/2">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817
                                    4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="bg-gray-50 border border-gray-300 text-sm text-gray-900 rounded-lg block w-full pl-10 p-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Search" autocomplete="off">
                    </div>
                </form>

                {{-- STATUS FILTER --}}
                <form method="GET" action="{{ route('dashboard.shippeds.index') }}" class="w-full md:w-1/4 ml-auto">
                    <select name="status" onchange="this.form.submit();"
                        class="bg-gray-50 border border-gray-300 text-sm text-gray-900 rounded-lg p-2 w-full focus:ring-primary-500 focus:border-primary-500">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Complete
                        </option>
                    </select>
                </form>
            </div>

            {{-- ORDER TABLE --}}
            <div class="overflow-x-auto">
                <table class="min-w-max text-sm text-left text-gray-900 whitespace-nowrap">
                    <thead class="text-xs uppercase bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Order ID</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Items</th>
                            <th class="px-4 py-3">Shipping</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Ordered At</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($orders as $order)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">{{ $no++ }}</td>
                                <td class="px-4 py-3 font-medium">{{ $order->midtrans_order_id ?? '-' }}</td>
                                <td class="px-4 py-3 font-medium">{{ $order->user->name }}</td>

                                {{-- ITEMS LIST --}}
                                <td class="px-4 py-3">
                                    <ul class="list-disc pl-4 space-y-1 text-sm">
                                        @foreach ($order->items as $item)
                                            <li>
                                                <span class="font-semibold">{{ $item->product_name }}</span><br>
                                                <span class="text-gray-900">
                                                    {{ $item->color ?? '-' }} / {{ $item->size ?? '-' }} ×
                                                    {{ $item->quantity }}
                                                </span><br>
                                                <span class="text-xs text-gray-900">
                                                    Rp{{ number_format($item->price, 0, ',', '.') }} each
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <td class="px-4 py-3">{{ $order->shipping_method ?? '-' }}</td>

                                {{-- PAYMENT STATUS --}}
                                <td class="px-4 py-3">
                                    <span class="text-green-600 font-semibold">Paid</span>
                                </td>

                                {{-- ORDER STATUS --}}
                                <td class="px-4 py-3">
                                    @if ($order->order_status === 'pending')
                                        <span class="text-yellow-500">Pending</span>
                                    @elseif ($order->order_status === 'shipped')
                                        <span class="text-blue-500">Shipped</span>
                                    @elseif ($order->order_status === 'completed')
                                        <span class="text-green-500">Completed</span>
                                    @else
                                        <span class="text-gray-500">Unknown</span>
                                    @endif
                                </td>

                                {{-- TANGGAL PEMESANAN --}}
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </td>

                                {{-- ACTION BUTTON --}}
                                <td
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap flex gap-2 items-center">
                                    <a href="{{ route('dashboard.shippeds.show', $order->id) }}"
                                        class="bg-blue-500 text-white px-2 py-1 rounded-lg hover:bg-blue-600">
                                        Detail
                                    </a>

                                    @if ($order->order_status === 'pending')
                                        <form action="{{ route('dashboard.shippeds.shipped', $order->id) }}"
                                            method="POST" id="shipped-form-{{ $order->id }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-2 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600"
                                                onclick="return confirm('Mark this order as shipped?');">
                                                Mark as Shipped
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard.layout>
