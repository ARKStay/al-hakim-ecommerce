<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserHistoryController extends Controller
{
    /**
     * Menampilkan daftar pesanan yang dimiliki oleh pengguna.
     */
    public function index()
    {
        $orders = Order::with(['orderItems.product', 'ratings']) // Tambahkan 'ratings'
            ->where('user_id', Auth::id())
            ->where('order_status', 'complete')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.history', [
            'title' => 'History Orders',
            'orders' => $orders,
        ]);
    }

    /**
     * Mengirimkan rating dan komentar untuk pesanan yang sudah diterima.
     */
    public function rateOrder(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Ambil data order berdasarkan ID
        $order = Order::with('orderItems.product')->findOrFail($validated['order_id']);

        // Pastikan order milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Loop semua produk dalam order dan kasih rating
        foreach ($order->orderItems as $item) {
            $product = $item->product;

            // Simpan rating untuk setiap produk di dalam order
            Rating::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);
        }

        // Update rating produk setelah semua rating disimpan
        foreach ($order->orderItems as $item) {
            $item->product->updateRatings();
        }

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }
}
