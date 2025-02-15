<?php

namespace App\Http\Controllers;

use App\Models\Sizes;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class DashboardProductsController extends Controller
{
    /**
     * Menampilkan daftar produk dengan filter pencarian.
     */
    public function index(Request $request)
    {
        // Mengambil produk berdasarkan filter pencarian
        $products = Product::filter($request->only('search'))->get();

        // Mengirimkan data produk ke tampilan
        return view('dashboard.products.index', ['title' => 'Product List', 'products' => $products]);
    }

    /**
     * Menampilkan form untuk menambahkan produk baru.
     */
    public function create()
    {
        // Menampilkan form tambah produk dengan data kategori dan ukuran
        return view('dashboard.Products.create', [
            'title' => 'Add Product',
            'categories' => Category::all(),
            'sizes' => Sizes::all()
        ]);
    }

    /**
     * Menyimpan produk baru yang diterima dari form.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',  // Unik slug untuk produk
            'category_id' => 'required|exists:categories,id',
            'image' => 'image|file|max:30720', // Validasi gambar
            'sizes_id' => 'required|exists:sizes,id',
            'color' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        // Jika ada gambar, simpan gambar ke storage
        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('product-images');
        }

        // Simpan data produk ke dalam database
        Product::create($validatedData);

        // Redirect ke halaman produk dengan pesan sukses
        return redirect('/dashboard/products')->with('Success', 'New product has been added!');
    }

    /**
     * Menampilkan detail produk dengan rating dan total rating.
     */
    public function show(Product $product)
    {
        // Menghitung rata-rata rating dan total rating produk
        $averageRating = $product->ratings()->avg('rating');  // Rata-rata rating
        $totalRatings = $product->ratings()->count();          // Total jumlah rating

        // Menampilkan data produk bersama dengan rating
        return view('dashboard.Products.show', [
            'product' => $product,
            'title' => 'Show Product',
            'categories' => Category::all(),
            'sizes' => Sizes::all(),
            'average_rating' => $averageRating, // Rata-rata rating produk
            'total_ratings' => $totalRatings,   // Total rating produk
        ]);
    }

    /**
     * Menampilkan form untuk mengedit produk.
     */
    public function edit(Product $product)
    {
        // Menampilkan form edit produk dengan data produk, kategori, dan ukuran
        return view('dashboard.Products.edit', [
            'title' => 'Edit Product',
            'product' => $product,
            'categories' => Category::all(),
            'sizes' => Sizes::all()
        ]);
    }

    /**
     * Memperbarui data produk yang sudah ada.
     */
    public function update(Request $request, Product $product)
    {
        // Aturan validasi untuk data yang akan diperbarui
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'image|file|max:30720', // Validasi gambar
            'sizes_id' => 'required|exists:sizes,id',
            'color' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ];

        // Jika slug diubah, pastikan slug tetap unik
        if ($request->slug != $product->slug) {
            $rules['slug'] = 'required|unique:products';
        }

        // Validasi input
        $validatedData = $request->validate($rules);

        // Jika ada gambar baru, hapus gambar lama dan simpan yang baru
        if ($request->file('image')) {
            if ($request->oldImage) {
                Storage::delete($request->oldImage); // Hapus gambar lama
            }
            $validatedData['image'] = $request->file('image')->store('product-images');
        }

        // Memperbarui data produk
        $product->update($validatedData);

        // Redirect ke halaman produk dengan pesan sukses
        return redirect('/dashboard/products')->with('Success', 'Product updated successfully!');
    }

    /**
     * Menghapus produk yang dipilih.
     */
    public function destroy(Product $product)
    {
        // Jika produk memiliki gambar, hapus gambar dari storage
        if ($product->image) {
            Storage::delete($product->image);
        }
        
        // Menghapus data produk
        $product->delete();

        // Redirect ke halaman produk dengan pesan sukses
        return redirect('/dashboard/products')->with('Success', 'Product has been deleted!');
    }

    /**
     * Mengecek dan menghasilkan slug berdasarkan nama produk.
     */
    public function checkSlug(Request $request)
    {
        // Membuat slug dari nama produk
        $slug = SlugService::createSlug(Product::class, 'slug', $request->name);

        // Menambahkan nomor acak pada slug
        $randomNumber = rand(1000, 9999);
        $slug .= '-' . $randomNumber;

        // Mengirimkan slug sebagai respons JSON
        return response()->json(['slug' => $slug]);
    }
}
