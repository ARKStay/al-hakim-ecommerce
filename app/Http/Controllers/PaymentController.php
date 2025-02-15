<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Cart_Item;
use App\Models\Product;
use Illuminate\Http\Request;
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
        // Validasi input
        $request->validate([
            'shipping' => 'required|string',
            'image' => 'required|image|max:30720', // Maksimal 30MB
        ]);

        // Ambil cart dengan status pending
        $cart = Cart::where('user_id', Auth::id())->where('status', 'pending')->first();
        if (!$cart) {
            return redirect()->route('user.cart')->withErrors('No pending cart found.');
        }

        // Simpan bukti pembayaran
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('payment-proofs', 'public');
        } else {
            return back()->withErrors(['image' => 'The uploaded file is invalid or missing.'])->withInput();
        }

        // Update stok produk
        $cart_items = Cart_Item::where('cart_id', $cart->id)->get();
        foreach ($cart_items as $cart_item) {
            $product = Product::find($cart_item->product_id);
            if ($product) {
                $product->stock -= $cart_item->quantity;
                $product->save();
            }
        }

        // Simpan data order
        $order = new Order;
        $order->user_id = Auth::id();
        $order->cart_id = $cart->id;
        $order->total_price = $cart->price;
        $order->shipping_method = $request->shipping;
        $order->image = $imagePath;
        $order->payment_status = 'pending';
        $order->order_status = 'pending';
        $order->save();

        // Ubah status cart
        $cart->status = 'completed';
        $cart->save();

        return redirect()->route('user.index')->with('success', 'Your payment has been successfully submitted and is awaiting approval.');
    }
}
