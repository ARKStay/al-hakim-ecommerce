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
        // Ambil semua pesanan user yang sedang login dengan relasi terkait
        $orders = Order::with('cart.items.product') // Eager loading
            ->where('user_id', Auth::id())
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
        $order = Order::findOrFail($validated['order_id']);

        // Pastikan order milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Ambil item produk dari order
        $product = $order->cart->items->first()->product;

        // Cek apakah produk sudah pernah diberi rating oleh user
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        // Jika sudah ada rating, pastikan produk dibeli lagi
        if ($existingRating) {
            // Cek jika user membeli produk ini ulang
            $lastPurchase = Order::where('user_id', Auth::id())
                ->where('order_status', 'complete')
                ->whereHas('cart.items', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->latest('created_at')
                ->first();
            if ($lastPurchase && $lastPurchase->created_at > $existingRating->created_at) {
                // Jika produk dibeli ulang, izinkan memberikan rating
                $rating = Rating::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'rating' => $validated['rating'],
                    'comment' => $validated['comment'],
                ]);
            } else {
                return redirect()->back()->with('error', 'You must purchase the product again to rate it.');
            }
        } else {
            // Jika produk belum dirating, langsung beri rating
            $rating = Rating::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);
        }

        // Update rata-rata rating produk
        $product->updateRatings();

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }
}
