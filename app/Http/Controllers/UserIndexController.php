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
            'products' => Product::inRandomOrder()->take(8)->get(),
            'banners' => Banner::where('status', 'on')->get(),
            'trendingProducts' => Product::with('variants')
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
        $product = Product::with('variants')->where('slug', $slug)->firstOrFail();

        return view('user.detail', [
            'title' => $product->name,
            'product' => $product,
        ]);
    }
}
