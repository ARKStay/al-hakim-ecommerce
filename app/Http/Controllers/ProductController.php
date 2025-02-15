<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Ambil semua input dari request untuk diteruskan ke filter
        return view('products', [
            'title' => 'Our Products',
            'products' => Product::latest()->filter(request()->all())->get()
        ]);
    }
}
