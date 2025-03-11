<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Banner;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Menampilkan halaman utama dengan produk acak dan banner aktif.
     */
    public function index()
    {
        return view('index', [
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
    public function product_detail($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();

        return view('product_detail', [
            'title' => $product->name,
            'product' => $product,
        ]);
    }

    /**
     * Menampilkan daftar produk.
     */
    public function products()
    {
        return view('products', [
            'title' => 'Our Products',
            'products' => Product::latest()->filter(request()->all())->get()
        ]);
    }
}
