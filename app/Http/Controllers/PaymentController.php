<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Cart_Item;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman pembayaran.
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->where('status', 'pending')->first();
        $cart_items = $cart ? Cart_Item::where('cart_id', $cart->id)->get() : [];
        $user = Auth::user();

        return view('user.payment.index', compact('cart', 'cart_items', 'user'));
    }


    /**
     * Memproses pembayaran dan menyimpan data order.
     */
    public function order(Request $request)
    {
        // Tarif ongkir berdasarkan jasa pengiriman
        $shipping_rates = [
            'JNE' => 15000,
            'J&T' => 12000,
            'POS' => 10000,
            'TIKI' => 13000,
            'Sicepat' => 11000,
        ];

        // Validasi input
        $request->validate([
            'shipping' => 'required|string|in:JNE,J&T,POS,TIKI,Sicepat',
            'image' => 'required|image|max:30720', // Maksimal 30MB
        ]);

        // Ambil cart dengan status pending
        $cart = Cart::where('user_id', Auth::id())->where('status', 'pending')->first();
        if (!$cart) {
            return redirect()->route('user.cart')->withErrors('No pending cart found.');
        }

        // Hitung ongkir
        $shipping_method = $request->shipping;
        $shipping_cost = $shipping_rates[$shipping_method] ?? 0;

        // Cek apakah gratis ongkir (syarat: belanja minimal 500k)
        $free_shipping_threshold = 500000;
        if ($cart->price >= $free_shipping_threshold) {
            $shipping_cost = 0;
        }

        // Simpan bukti pembayaran
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('payment-proofs', 'public');
        } else {
            return back()->withErrors(['image' => 'The uploaded file is invalid or missing.'])->withInput();
        }

        // Simpan data order
        $order = new Order;
        $order->user_id = Auth::id();
        $order->cart_id = $cart->id;
        $order->total_price = $cart->price + $shipping_cost;
        $order->shipping_method = $shipping_method;
        $order->shipping_cost = $shipping_cost;
        $order->image = $imagePath;
        $order->payment_status = 'pending';
        $order->order_status = 'pending';
        $order->save();

        // Simpan setiap produk dari cart ke tabel order_items
        $cart_items = Cart_Item::where('cart_id', $cart->id)->get();
        foreach ($cart_items as $cart_item) {
            $product = Product::find($cart_item->product_id);
            if ($product) {
                // Simpan ke order_items
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $cart_item->quantity;
                $orderItem->price = $product->price;
                $orderItem->color = $product->color;
                $orderItem->sizes_id = $product->sizes_id;
                $orderItem->save();

                // Kurangi stok produk
                $product->stock -= $cart_item->quantity;
                $product->save();
            }
        }

        // Ubah status cart jadi completed
        $cart->status = 'completed';
        $cart->save();

        return redirect()->route('user.index')->with('success', 'Your payment has been successfully submitted and is awaiting approval.');
    }
}
