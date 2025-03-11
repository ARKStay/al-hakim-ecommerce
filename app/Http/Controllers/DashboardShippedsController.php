<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardShippedsController extends Controller
{
    /**
     * Menampilkan daftar order yang sudah dibayar (payment_status = approved),
     * dengan opsi filter berdasarkan status order dan pencarian.
     */
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'user']); // Load relasi produk dan user

        // Filter hanya menampilkan order dengan payment_status 'approved'
        $query->where('payment_status', 'approved');

        // Jika ada filter status order, tambahkan kondisi untuk status order
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('order_status', $request->status);
        }

        // Jika ada pencarian berdasarkan cart_id, user name, atau order_status
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('cart_id', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('order_status', 'like', '%' . $search . '%'); // Filter berdasarkan status
            });
        }

        // Mengambil data order berdasarkan filter yang sudah diterapkan
        $orders = $query->get();

        // Mengirimkan data order ke tampilan
        return view('dashboard.shippeds.index', compact('orders'));
    }

    /**
     * Memperbarui status order menjadi 'complete'.
     */
    public function complete($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['order_status' => 'complete']); // Perbarui status order menjadi 'complete'

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('dashboard.shippeds.index')->with('success', 'Order status updated to complete.');
    }

    /**
     * Menghapus order dan gambar terkait dari storage.
     */
    public function delete($id)
    {
        $order = Order::findOrFail($id);

        // Jika order memiliki gambar, hapus gambar tersebut dari storage
        if ($order->image) {
            Storage::delete($order->image);
        }

        // Menghapus data order dari database
        $order->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('dashboard.shippeds.index')->with('success', 'Order deleted successfully.');
    }
}
