<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardShippedsController extends Controller
{
    /**
     * Show list of paid orders, filterable by status and search keyword.
     */
    public function index(Request $request)
    {
        $query = Order::with([
            'items.product',     // Produk yang dibeli
            'items.variant',     // Variasi produk (warna, ukuran, dll)
            'user'               // Pembeli
        ]);

        // Show only paid orders
        $query->where('payment_status', 'paid');

        // Filter berdasarkan order status (optional)
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('order_status', $request->status);
        }

        // Filter berdasarkan search query
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('midtrans_order_id', 'like', '%' . $search . '%')
                    ->orWhere('shipping_method', 'like', '%' . $search . '%')
                    ->orWhere('payment_status', 'like', '%' . $search . '%')
                    ->orWhere('order_status', 'like', '%' . $search . '%')
                    ->orWhere('created_at', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($qUser) use ($search) {
                        $qUser->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('items', function ($qItem) use ($search) {
                        $qItem->where('product_name', 'like', '%' . $search . '%')
                            ->orWhere('color', 'like', '%' . $search . '%')
                            ->orWhere('size', 'like', '%' . $search . '%');
                    });
            });
        }

        // Ambil data terbaru duluan
        $orders = $query->latest()->get();

        return view('dashboard.shippeds.index', compact('orders'));
    }

    /**
     * Update order status to 'complete'.
     */
    public function shipped($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['order_status' => 'shipped']);

        return redirect()->route('dashboard.shippeds.index')
            ->with('success', 'Order marked as shipped!');
    }

    public function show($id)
    {
        $order = Order::with([
            'items.product',
            'items.variant',
            'user'
        ])->findOrFail($id);

        return view('dashboard.shippeds.show', compact('order'));
    }

    /**
     * Delete order and related image from storage (if any).
     */
    public function delete($id)
    {
        $order = Order::findOrFail($id);

        if ($order->image) {
            Storage::delete($order->image);
        }

        $order->delete();

        return redirect()->route('dashboard.shippeds.index')
            ->with('success', 'Order deleted successfully.');
    }
}
