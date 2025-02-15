<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Mendapatkan bulan saat ini
        $currentMonth = Carbon::now()->month;

        // Total Pendapatan Bulanan (hanya pesanan dengan status 'complete')
        $monthlyRevenue = Order::whereMonth('created_at', $currentMonth)
            ->where('order_status', 'complete')
            ->sum('total_price');

        // Total Pembeli Bulanan (hanya pesanan dengan status 'complete')
        $monthlyBuyers = Order::whereMonth('created_at', $currentMonth)
            ->where('order_status', 'complete')
            ->distinct('user_id')
            ->count('user_id');

        // Total Transaksi Bulanan (hanya pesanan dengan status 'complete')
        $monthlyOrders = Order::whereMonth('created_at', $currentMonth)
            ->where('order_status', 'complete')
            ->count();

        // Data pendapatan harian untuk bulan ini
        $dailyRevenue = Order::selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
            ->whereMonth('created_at', $currentMonth)
            ->where('order_status', 'complete')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Menghitung jumlah pesanan dengan status pembayaran 'approved' dan 'rejected'
        $orders = Order::whereMonth('created_at', Carbon::now()->month)
            ->selectRaw('sum(case when payment_status = "approved" then 1 else 0 end) as approved, 
            sum(case when payment_status = "rejected" then 1 else 0 end) as rejected')
            ->first();

        // Jika nilai null, set ke 0
        $approved = $orders->approved ?? 0;
        $rejected = $orders->rejected ?? 0;

        // Mendapatkan 5 transaksi terakhir dengan informasi pembeli dan total pengeluaran
        $latestTransactions = Order::where('order_status', 'complete')
            ->with('user') // Mengambil relasi dengan User
            ->latest() // Mengurutkan berdasarkan waktu terbaru
            ->limit(5) // Ambil 5 transaksi terakhir
            ->get(['user_id', 'total_price', 'created_at']);

        // Mengirimkan data ke tampilan dashboard
        return view('dashboard.index', [
            'title' => 'Analytics Dashboard',
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyBuyers' => $monthlyBuyers,
            'monthlyOrders' => $monthlyOrders,
            'dailyRevenue' => $dailyRevenue,
            'latestTransactions' => $latestTransactions,
            'approved' => $approved,
            'rejected' => $rejected,
        ]);
    }
}
