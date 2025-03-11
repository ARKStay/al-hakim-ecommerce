<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; 
use Illuminate\Support\Facades\Auth;

class UserOrdersController extends Controller
{
    /**
     * Menampilkan daftar pesanan user yang sedang login dengan eager loading.
     */
    public function index()
    {
        // Ambil semua pesanan user yang sedang login dengan eager loading
        $orders = Order::with('orderItems.product') // Eager load relasi dengan cart, items, dan product
            ->where('user_id', Auth::id()) // Filter berdasarkan user yang sedang login
            ->orderBy('created_at', 'desc') // Urutkan pesanan berdasarkan tanggal dibuat, terbaru di atas
            ->get();

        return view('user.orders', [
            'title' => 'My Orders', // Judul halaman
            'orders' => $orders, // Kirim data pesanan ke tampilan
        ]);
    }
}
