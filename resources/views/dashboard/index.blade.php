<x-dashboard.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="p-6 bg-white rounded-lg shadow-md">

        <!-- Overview Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Monthly Revenue -->
            <div class="bg-blue-100 p-8 rounded-lg shadow-md">
                <p class="text-gray-600 font-semibold">Monthly Revenue</p>
                <h2 class="text-4xl font-bold text-blue-600">Rp{{ number_format($monthlyRevenue, 0, ',', '.') }}</h2>
            </div>

            <!-- Monthly Buyers -->
            <div class="bg-green-100 p-8 rounded-lg shadow-md">
                <p class="text-gray-600 font-semibold">Total Buyers (This Month)</p>
                <h2 class="text-4xl font-bold text-green-600">{{ $monthlyBuyers }}</h2>
            </div>

            <!-- Monthly Orders -->
            <div class="bg-yellow-100 p-8 rounded-lg shadow-md">
                <p class="text-gray-600 font-semibold">Total Orders (This Month)</p>
                <h2 class="text-4xl font-bold text-yellow-600">{{ $monthlyOrders }}</h2>
            </div>
        </div>


        <!-- Payment Status Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <!-- Approved Payments -->
            <div class="bg-green-100 p-6 rounded-lg shadow-md">
                <p class="text-gray-600 font-semibold">Approved Payments</p>
                <h2 class="text-3xl font-bold text-green-600">{{ $approved ?? 0 }}</h2>
            </div>

            <!-- Rejected Payments -->
            <div class="bg-red-100 p-6 rounded-lg shadow-md">
                <p class="text-gray-600 font-semibold">Rejected Payments</p>
                <h2 class="text-3xl font-bold text-red-600">{{ $rejected ?? 0 }}</h2>
            </div>
        </div>

        <!-- Latest Transactions Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Latest Transactions</h3>
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="text-left">
                        <th class="px-4 py-2">Buyer</th>
                        <th class="px-4 py-2">Total Expenditure (Rp)</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latestTransactions as $transaction)
                        <tr>
                            <td class="px-4 py-2">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $transaction->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Revenue Graph Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Daily Revenue (This Month)</h3>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dailyRevenue = @json($dailyRevenue);
        const labels = dailyRevenue.map(data => data.date);
        const revenueData = dailyRevenue.map(data => data.revenue);

        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Daily Revenue',
                    data: revenueData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Revenue (Rp)'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-dashboard.layout>
