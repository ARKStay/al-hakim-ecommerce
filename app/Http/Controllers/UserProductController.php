<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart_Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProductController extends Controller
{
    /**
     * Menampilkan daftar produk dengan filter.
     */
    public function index()
    {
        // Ambil semua input dari request untuk diteruskan ke filter
        return view('user.products', [
            'title' => 'Our Products',
            'products' => Product::latest()->filter(request()->all())->get() // Filter produk berdasarkan request
        ]);
    }

    /**
     * Menambahkan produk ke keranjang.
     */
    public function cart(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->first();
    
        // Validasi apakah produk tersedia
        if (!$product) {
            return redirect('user/products/')->with('error', 'Product not found.');
        }
    
        // Ambil cart yang sedang pending
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::user()->id, 'status' => 'pending'],
            ['price' => 0]
        );
    
        // Ambil item yang ada di keranjang
        $cart_item = Cart_Item::where('product_id', $product->id)->where('cart_id', $cart->id)->first();
    
        // Hitung total kuantitas jika item sudah ada di keranjang
        $current_quantity = $cart_item ? $cart_item->quantity : 0;
        $new_total_quantity = $current_quantity + $request->quantity;
    
        // Validasi apakah melebihi stok
        if ($new_total_quantity > $product->stock) {
            return redirect('user/products/')->with('error', 'The requested quantity exceeds available stock.');
        }
    
        // Tambahkan item ke keranjang atau perbarui item yang sudah ada
        if ($cart_item) {
            $cart_item->quantity = $new_total_quantity;
            $cart_item->price = $new_total_quantity * $product->price;
            $cart_item->save();
        } else {
            Cart_Item::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $request->quantity * $product->price,
            ]);
        }
    
        // Perbarui total harga keranjang
        $cart->price = Cart_Item::where('cart_id', $cart->id)->sum('price');
        $cart->save();
    
        return redirect('user/products/')->with('success', 'Product added to cart successfully.');
    }

    /**
     * Menampilkan halaman checkout dengan daftar item di keranjang.
     */
    public function check_out()
    {
        $cart = Cart::where('user_id', Auth::user()->id)->where('status', 'pending')->first();
        $cart_items = [];

        if (!empty($cart)) {
            $cart_items = Cart_Item::where('cart_id', $cart->id)->get();
        }

        return view('user.cart.index', compact('cart', 'cart_items'));
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function delete($id)
    {
        $cart_item = Cart_Item::where('id', $id)->first();

        $cart = Cart::where('id', $cart_item->cart_id)->first();
        $cart->price = $cart->price - $cart_item->price; // Update harga total setelah item dihapus
        $cart->update();

        $cart_item->delete(); // Hapus item dari keranjang

        return redirect('user/cart')->with('success', 'Item successfully removed from your cart.');
    }

    /**
     * Mengonfirmasi checkout dan memastikan user memiliki alamat dan nomor telepon.
     */
    public function confirm_check_out()
    {
        $user = User::where('id', Auth::user()->id)->first();

        // Cek apakah alamat dan nomor telepon sudah diisi
        if (empty($user->address)) {
            return redirect('user/profile')->with('info', 'Please update your address before checking out.');
        }

        if (empty($user->phone)) {
            return redirect('user/profile')->with('info', 'Please update your phone number before checking out.');
        }

        return redirect('user/payment'); // Lanjut ke halaman pembayaran
    }
}
