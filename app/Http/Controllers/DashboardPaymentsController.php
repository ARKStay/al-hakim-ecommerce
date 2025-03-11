<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardPaymentsController extends Controller
{
    /**
     * Menampilkan daftar pembayaran dengan filter berdasarkan status dan pencarian.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Jika status pembayaran bukan 'all', maka filter berdasarkan status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('payment_status', $request->status);
        }

        // Filter pencarian berdasarkan order number, username, atau status pembayaran
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('cart_id', 'like', '%' . $search . '%') // Filter berdasarkan cart_id
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%'); // Filter berdasarkan nama user
                    })
                    ->orWhere('payment_status', 'like', '%' . $search . '%'); // Filter berdasarkan status pembayaran
            });
        }

        // Ambil data pesanan yang sudah difilter
        $orders = $query->select('id', 'cart_id', 'user_id', 'image', 'total_price', 'shipping_cost', 'payment_status', 'created_at', 'updated_at')
            ->with('user')
            ->get();

        // Mengirimkan data pesanan ke tampilan
        return view('dashboard.payments.index', compact('orders'));
    }

    /**
     * Menyetujui pembayaran dan mengubah status pembayaran menjadi 'approved'.
     */
    public function approve($id)
    {
        $order = Order::findOrFail($id); // Cari order berdasarkan ID
        $order->update(['payment_status' => 'approved']); // Ubah status menjadi approved

        // Redirect ke halaman pembayaran dengan pesan sukses
        return redirect()->route('dashboard.payments')->with('success', 'Payment approved successfully.');
    }

    /**
     * Menolak pembayaran dan mengubah status pembayaran menjadi 'rejected'.
     */
    public function reject($id)
    {
        $order = Order::findOrFail($id); // Cari order berdasarkan ID
        $order->update(['payment_status' => 'rejected']); // Ubah status menjadi rejected

        // Redirect ke halaman pembayaran dengan pesan sukses
        return redirect()->route('dashboard.payments')->with('success', 'Payment rejected successfully.');
    }

    /**
     * Menghapus pesanan dan menghapus file gambar terkait dari storage.
     */
    public function delete($id)
    {
        // Cari order berdasarkan ID
        $order = Order::findOrFail($id);

        // Cek jika ada gambar yang terkait dengan order, lalu hapus dari storage
        if ($order->image) {
            Storage::delete($order->image);
        }

        // Hapus data order dari database
        $order->delete();

        // Redirect ke halaman pembayaran dengan pesan sukses
        return redirect()->route('dashboard.payments')->with('success', 'Payment deleted successfully.');
    }
}
