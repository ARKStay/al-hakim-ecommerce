<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class UserOrdersController extends Controller
{
    /**
     * Menampilkan daftar pesanan user yang sedang login dengan eager loading.
     */
    public function index()
    {
        $orders = Order::with('items.product.ratings') // ambil product dan ratingnya
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('user.orders', [
            'title' => 'My Orders',
            'orders' => $orders,
        ]);
    }

    /**
     * Tandai pesanan sebagai diterima.
     */
    public function markReceived($id)
    {
        $order = Order::findOrFail($id);

        if ($order->order_status !== 'shipped') {
            return back()->with('error', 'Order tidak bisa diubah statusnya.');
        }

        $order->order_status = 'completed';
        $order->save();

        return back()->with('success', 'Terima kasih! Pesanan sudah ditandai sebagai diterima.');
    }

    /**
     * Tampilkan form review untuk satu order.
     */
    public function review($orderId)
    {
        $order = Order::with('items.product')
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Pastikan order sudah completed
        if ($order->order_status !== 'completed') {
            return redirect()->route('orders.index')->with('error', 'Order belum bisa direview.');
        }

        return view('user.review', [
            'title' => 'Review Order',
            'order' => $order,
        ]);
    }

    /**
     * Store review untuk satu order.
     */
    public function storeReview(Request $request, $orderId)
    {
        $order = Order::with('items.product')
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->order_status !== 'completed') {
            return redirect()->route('user.order')->with('error', 'Order cannot be reviewed yet.');
        }

        $validated = $request->validate([
            'ratings.*.product_id' => 'required|exists:products,id',
            'ratings.*.rating' => 'required|integer|min:1|max:5',
            'ratings.*.comment' => 'nullable|string|max:500',
        ]);

        foreach ($validated['ratings'] as $item) {
            $exists = Rating::where('user_id', Auth::id())
                ->where('product_id', $item['product_id'])
                ->exists();

            if (!$exists) {
                $rating = Rating::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item['product_id'],
                    'rating' => $item['rating'],
                    'comment' => $item['comment'] ?? null,
                ]);
                $product = $rating->product;
                $product->total_ratings = $product->ratings()->count();
                $product->average_rating = $product->ratings()->avg('rating');
                $product->save();
            }
        }
        return redirect()->route('user.order')->with('success', 'Review submitted successfully!');
    }
}
