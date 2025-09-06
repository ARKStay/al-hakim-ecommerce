<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: normal;
        }

        .period {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
        }

        h3 {
            margin-top: 20px;
            margin-bottom: 5px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 11px;
        }

        th {
            background: #f3f3f3;
        }

        .summary {
            margin-top: 15px;
        }

        .summary p {
            margin: 2px 0;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Sales Report</h1>
        <h2>Al Hakim Store</h2>
    </div>

    <div class="period">
        <p><strong>Period:</strong>
            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        </p>
    </div>

    <div class="summary">
        <p><strong>Total Sales:</strong> {{ $totalSales }}</p>
    </div>

    <h3>Orders</h3>
    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price (Rp)</th>
                <th>Total Payment (Rp)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <!-- Row utama: Customer, Total Payment, Date -->
                <tr>
                    <td rowspan="{{ $order->items->count() }}">{{ $order->user->name }}</td>
                    <td>{{ $order->items->first()->product->name }}
                        @if ($order->items->first()->variant)
                            - {{ $order->items->first()->variant->name }}
                        @endif
                    </td>
                    <td>{{ $order->items->first()->quantity }}</td>
                    <td>Rp{{ number_format($order->items->first()->price, 0, ',', '.') }}</td>
                    <td rowspan="{{ $order->items->count() }}">
                        Rp{{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                    <td rowspan="{{ $order->items->count() }}">
                        {{ $order->created_at->format('d M Y') }}
                    </td>
                </tr>

                <!-- Sisanya: Product lain -->
                @foreach ($order->items->skip(1) as $item)
                    <tr>
                        <td>{{ $item->product->name }}
                            @if ($item->variant)
                                - {{ $item->variant->name }}
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">No sales found for this period</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:left; font-weight:bold;">Total Revenue</td>
                <td colspan="2" style="font-weight:bold;">
                    Rp{{ number_format($totalRevenue, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>

    </table>

</body>

</html>
