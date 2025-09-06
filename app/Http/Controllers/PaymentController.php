<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use App\Models\Cart;
use Midtrans\Config;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman pembayaran.
     */
    public function index()
    {
        $user = Auth::user();
        $district_id = $user->district_id;

        $cart = Cart::with(['items.variant.product'])
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        $cart_items = $cart ? $cart->items : collect();

        $totalWeight = $cart_items->sum(function ($item) {
            return $item->variant->weight * $item->quantity;
        });

        return view('user.payment.index', [
            'user' => $user,
            'cart' => $cart,
            'cart_items' => $cart_items,
            'totalWeight' => $totalWeight,
            'district_id' => $district_id,
        ]);
    }

    /**
     * Menghasilkan Snap Token untuk Midtrans.
     */
    public function getSnapToken(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        if (!$request->shipping_price || !$request->shipping_service) {
            return response()->json([
                'error' => 'Data ongkir belum lengkap!',
                'data_received' => $request->all()
            ], 422);
        }

        $user = Auth::user();
        $cart = Cart::with(['items.variant.product'])
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$cart) {
            return response()->json(['error' => 'Keranjang tidak ditemukan.'], 404);
        }

        $item_details = [];

        foreach ($cart->items as $item) {
            if (!$item->variant) {
                dd("Cart item ID {$item->id} tidak punya variant. Cek database.");
            }

            $item_details[] = [
                'id'       => 'VAR-' . $item->variant->id,
                'price'    => (int) $item->variant->price,
                'quantity' => $item->quantity,
                'name'     => $item->variant->product->name . ' - ' . $item->variant->color,
            ];
        }

        // Tambahin ongkir di akhir
        $item_details[] = [
            'id'       => 'ONGKIR',
            'price'    => (int) $request->shipping_price,
            'quantity' => 1,
            'name'     => 'Biaya Pengiriman (' . $request->shipping_service . ')',
        ];

        $total = collect($item_details)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $midtrans_order_id = 'ORDER-' . strtoupper(Str::random(10));
        session(['midtrans_order_id' => $midtrans_order_id]);

        $params = [
            'transaction_details' => [
                'order_id' => $midtrans_order_id,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email ?? 'no-email@example.com',
                'phone' => $user->phone ?? '081234567890',
            ],
            'item_details' => $item_details,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            session([
                'midtrans_order_id' => $midtrans_order_id,
                'payment_token' => $snapToken,
                'payment_type' => 'snap', // default assumption
                'payment_url' => 'https://app.midtrans.com/snap/v2/vtweb/' . $snapToken,
            ]);
            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menyimpan data order setelah pembayaran berhasil.
     */
    public function order(Request $request)
    {
        $request->validate([
            'shipping_service' => 'required',
            'shipping_price' => 'required|numeric',
        ]);

        $cart = Cart::with(['items.variant.product'])
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (!$cart) {
            return redirect()->route('user.cart')->withErrors('Tidak ada keranjang pending.');
        }

        $shipping_method = $request->shipping_service;
        $shipping_cost = (int) $request->shipping_price;

        $midtrans_order_id = session('midtrans_order_id');
        if (!$midtrans_order_id) {
            return redirect()->back()->withErrors('Terjadi kesalahan. Silakan ulangi proses pembayaran.');
        }

        $order = Order::create([
            'user_id'           => Auth::id(),
            'cart_id'           => $cart->id,
            'total_price'       => $cart->total_price + $shipping_cost,
            'shipping_method'   => $shipping_method,
            'shipping_cost'     => $shipping_cost,
            'payment_status'    => 'paid',
            'order_status'      => 'pending',
            'midtrans_order_id' => $midtrans_order_id,
            'payment_token'     => session('payment_token'),
            'payment_type'      => session('payment_type'),
            'payment_url'       => session('payment_url'),
        ]);

        foreach ($cart->items as $cart_item) {
            $variant = $cart_item->variant;

            if ($variant) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'product_name'       => $variant->product->name,
                    'color'              => $variant->color,
                    'size'               => $variant->size,
                    'variant_image'      => $variant->variant_image,
                    'weight'             => $variant->weight,
                    'price'              => $variant->price,
                    'quantity'           => $cart_item->quantity,
                ]);

                $variant->stock -= $cart_item->quantity;
                $variant->save();

                $product = $variant->product;
                if ($product) {
                    $product->sold += $cart_item->quantity;
                    $product->save();
                }
            }
        }

        $cart->status = 'completed';
        $cart->save();

        session()->forget('midtrans_order_id');

        return redirect()->route('user.index')->with('success', 'Your payment has been submitted and is now pending approval.');
    }
}
