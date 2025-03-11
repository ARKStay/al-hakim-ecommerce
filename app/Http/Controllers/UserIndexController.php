<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;

class UserIndexController extends Controller
{
    /**
     * Menampilkan halaman utama untuk user dengan produk acak dan banner aktif.
     */
    public function index()
    {
        return view('user.index', [
            'title' => 'Home',
            'products' => Product::with(['category'])->inRandomOrder()->take(8)->get(),
            'banners' => Banner::where('status', 'on')->get(),
            'trendingProducts' => Product::with(['category'])
                ->orderBy('average_rating', 'desc')
                ->take(4)
                ->get(),
        ]);
    }

    /**
     * Menampilkan detail produk berdasarkan slug.
     */
    public function detail($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail(); // Mengambil produk berdasarkan slug

        return view('user.detail', [
            'title' => $product->name, // Judul halaman sesuai dengan nama produk
            'product' => $product, // Mengirimkan data produk ke tampilan
        ]);
    }
}
