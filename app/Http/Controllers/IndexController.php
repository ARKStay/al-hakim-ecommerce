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
            'products' => Product::inRandomOrder()->take(8)->get(), // HAPUS 'with category'
            'banners' => Banner::where('status', 'on')->get(),
            'trendingProducts' => Product::with('variants')->orderBy('average_rating', 'desc')->take(4)->get(),
        ]);
    }

    /**
     * Menampilkan detail produk berdasarkan slug.
     */
    public function product_detail($slug)
    {
        $product = Product::with(['variants'])->where('slug', $slug)->firstOrFail();

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
            'products' => Product::with('variants')->latest()->get(),
        ]);
    }
}
