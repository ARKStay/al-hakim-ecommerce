<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class UserHistoryController extends Controller
{
    /**
     * Menampilkan daftar pesanan (history).
     */
    public function index()
    {
        $orders = Order::with([
            'orderItems.product',
            'orderItems.variant', // ambil data varian produk
        ])
            ->where('user_id', Auth::id())
            ->where('order_status', 'completed') // completed aja buat history
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.history', [
            'title' => 'Transaction History',
            'orders' => $orders,
        ]);
    }
}
