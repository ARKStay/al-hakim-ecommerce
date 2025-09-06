<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProductController extends Controller
{
    /**
     * Menampilkan daftar produk dengan filter.
     */
    public function index()
    {
        return view('user.products', [
            'title' => 'Our Products',
            'products' => Product::with('variants')->latest()->filter(request()->all())->get()
        ]);
    }

    /**
     * Menambahkan produk ke keranjang.
     */
    public function cart(Request $request, $slug)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
            'color' => 'required|string',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);

        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'pending'],
            ['total_price' => 0]
        );

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        $currentQuantity = $cartItem ? $cartItem->quantity : 0;
        $newTotalQuantity = $currentQuantity + $request->quantity;

        if ($newTotalQuantity > $variant->stock) {
            return redirect('user/products/')->with('error', 'The requested quantity exceeds available stock.');
        }

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $newTotalQuantity,
                'price' => $variant->price, // tetap satuan
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
                'product_name' => $variant->product->name,
                'color' => $variant->color,
                'size' => $variant->size,
                'weight' => $variant->weight,
                'price' => $variant->price,
                'quantity' => $request->quantity
            ]);
        }

        // Hitung ulang total harga
        $cart->total_price = $cart->items->sum(fn($item) => $item->price * $item->quantity);
        $cart->save();

        return redirect('user/products/')->with('success', 'Product added to cart successfully.');
    }

    /**
     * Menampilkan halaman checkout dengan daftar item di keranjang.
     */
    public function check_out()
    {
        $cart = Cart::with('items.variant.product')
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        return view('user.cart.index', [
            'cart' => $cart,
            'cartitems' => $cart ? $cart->items : []
        ]);
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function delete($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cart = $cartItem->cart;

        $cartItem->delete();

        // Hitung ulang total cart setelah item dihapus
        $cart->total_price = $cart->items->sum(fn($item) => $item->price * $item->quantity);
        $cart->save();

        return redirect('user/cart')->with('success', 'Item successfully removed from your cart.');
    }

    /**
     * Mengonfirmasi checkout dan memastikan user memiliki alamat dan nomor telepon.
     */
    public function confirm_check_out()
    {
        $user = Auth::user();

        if (empty($user->address)) {
            return redirect('user/profile')->with('info', 'Please update your address before checking out.');
        }

        if (empty($user->phone)) {
            return redirect('user/profile')->with('info', 'Please update your phone number before checking out.');
        }

        return redirect('user/payment');
    }
}
