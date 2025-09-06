<x-dashboard.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="p-6 bg-white rounded-lg shadow-md">

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
                        position: 'center'
                    });
                });
            </script>
        @endif

        <form action="{{ route('dashboard.print') }}" method="GET" target="_blank"
            class="mb-6 bg-gray-50 p-4 rounded-lg shadow flex flex-wrap justify-between items-end gap-4">

            <div class="flex flex-wrap gap-4">
                <!-- Pilihan Periode -->
                <div class="flex flex-col">
                    <label for="range" class="text-sm font-semibold text-gray-700 mb-1">Choose Report Period</label>
                    <select name="range" id="range"
                        class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        <option value="" disabled selected>-- Choose Period --</option>
                        <option value="all">All Time</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>

                <!-- Custom Date Range -->
                <div id="custom-filters" class="hidden flex gap-4">
                    <div class="flex flex-col">
                        <label for="start_date" class="text-sm font-semibold text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" id="start_date"
                            class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col">
                        <label for="end_date" class="text-sm font-semibold text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" id="end_date"
                            class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Button -->
            <div class="ml-auto">
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                    Print Report
                </button>
            </div>
        </form>

        <!-- Overview Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Today's Sales -->
            <div class="bg-blue-100 p-6 rounded-lg shadow-md">
                <p class="text-gray-600 font-semibold">Today's Sales</p>
                <h2 class="text-3xl font-bold text-blue-600">{{ $todaySales }}</h2>
            </div>

            <!-- Total Sales -->
            <div class="bg-purple-100 p-6 rounded-lg shadow-md">
                <p class="text-gray-600 font-semibold">Total Sales</p>
                <h2 class="text-3xl font-bold text-purple-600">{{ $totalSales }}</h2>
            </div>

            <!-- Today's Revenue -->
            <div class="bg-green-100 p-6 rounded-lg shadow-md">
                <p class="text-gray-600 font-semibold">Today's Revenue</p>
                <h2 class="text-3xl font-bold text-green-600">Rp{{ number_format($todayRevenue, 0, ',', '.') }}</h2>
            </div>

            <!-- Total Revenue -->
            <div class="bg-yellow-100 p-6 rounded-lg shadow-md">
                <p class="text-gray-600 font-semibold">Total Revenue</p>
                <h2 class="text-3xl font-bold text-yellow-600">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h2>
            </div>
        </div>

        <!-- Latest Orders -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Latest Orders</h3>
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="text-left">
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Total Amount (Rp)</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latestOrders as $order)
                        <tr>
                            <td class="px-4 py-2">{{ $order->user->name }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Daily Revenue Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Daily Revenue (This Month)</h3>
                <canvas id="revenueChart"></canvas>
            </div>

            <!-- Daily Sales Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Daily Sales (This Month)</h3>
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dailyRevenue = @json($dailyRevenue);
        const dailySales = @json($dailySales);

        // Revenue chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: dailyRevenue.map(d => d.date),
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: dailyRevenue.map(d => d.revenue),
                    borderColor: 'rgba(34, 197, 94, 1)',
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true
            }
        });

        // Sales chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: dailySales.map(d => d.date),
                datasets: [{
                    label: 'Sales',
                    data: dailySales.map(d => d.sales),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const rangeSelect = document.getElementById("range");
            const customFilters = document.getElementById("custom-filters");

            function toggleCustomFilters() {
                if (rangeSelect.value === "custom") {
                    customFilters.classList.remove("hidden");
                    customFilters.classList.add("flex");
                } else {
                    customFilters.classList.add("hidden");
                    customFilters.classList.remove("flex");
                }
            }

            rangeSelect.addEventListener("change", toggleCustomFilters);
            toggleCustomFilters();
        });
    </script>

</x-dashboard.layout>
