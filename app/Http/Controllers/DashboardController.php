<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Today’s Sales (complete orders)
        $todaySales = Order::whereDate('created_at', $today)
            ->where('order_status', 'completed')
            ->count();

        // Total Sales
        $totalSales = Order::where('order_status', 'completed')->count();

        // Today’s Revenue
        $todayRevenue = Order::whereDate('created_at', $today)
            ->where('order_status', 'completed')
            ->sum('total_price');

        // Total Revenue
        $totalRevenue = Order::where('order_status', 'completed')->sum('total_price');

        // Daily Revenue (this month)
        $dailyRevenue = Order::selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('order_status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Daily Sales (this month)
        $dailySales = Order::selectRaw('DATE(created_at) as date, COUNT(*) as sales')
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('order_status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Latest Orders
        $latestOrders = Order::where('order_status', 'completed')
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'title' => 'Analytics Dashboard',
            'todaySales' => $todaySales,
            'totalSales' => $totalSales,
            'todayRevenue' => $todayRevenue,
            'totalRevenue' => $totalRevenue,
            'dailyRevenue' => $dailyRevenue,
            'dailySales' => $dailySales,
            'latestOrders' => $latestOrders,
        ]);
    }

    public function printReport(Request $request)
    {
        $range = $request->get('range', 'all');
        $startDate = null;
        $endDate = null;

        if (!$request->range) {
            return redirect()->back()->with('error', 'Please select a period first!');
        }

        switch ($range) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->get('start_date'));
                $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
                break;
            default: // all
                $startDate = Order::min('created_at');
                $endDate = Order::max('created_at');
                break;
        }

        $orders = Order::where('order_status', 'completed')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $totalSales = $orders->count();
        $totalRevenue = $orders->sum('total_price');

        $pdf = Pdf::loadView('dashboard.print', [
            'orders' => $orders,
            'totalSales' => $totalSales,
            'totalRevenue' => $totalRevenue,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        return $pdf->stream('sales-report.pdf');
    }
}
