<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        $products = Product::with('variants')
            ->withAvg('ratings', 'rating')     // Rata-rata rating
            ->withCount('ratings')             // Total rating
            ->filter($request->only('search')) // Filter search
            ->get();

        return view('dashboard.products.index', [
            'title' => 'Product List',
            'products' => $products
        ]);
    }


    /**
     * Menampilkan form untuk menambahkan produk baru.
     */
    public function create()
    {
        return view('dashboard.products.create', [
            'title' => 'Add Product',
        ]);
    }

    /**
     * Menyimpan produk baru yang diterima dari form.
     */
    public function store(Request $request)
    {
        // Validasi untuk produk
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'image' => 'image|file|max:30720',
            'description' => 'nullable|string',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.size' => 'required|string|max:10',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.weight' => 'required|numeric|min:0',
            'variants.*.variant_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan gambar produk utama jika ada
        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('product-images');
        }

        $product = Product::create([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'image' => $validatedData['image'] ?? null,
            'description' => $validatedData['description'] ?? null,
        ]);

        // Simpan product_variants
        if ($request->has('variants')) {
            // Simpan gambar per warna hanya sekali
            $colorImageMap = [];

            foreach ($request->variants as $variant) {
                $color = $variant['color'];

                if (!isset($colorImageMap[$color])) {
                    if (isset($variant['variant_image']) && $variant['variant_image']) {
                        $colorImageMap[$color] = $variant['variant_image']->store('variant-images');
                    } else {
                        $colorImageMap[$color] = null;
                    }
                }

                $product->variants()->create([
                    'color' => $color,
                    'size' => $variant['size'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                    'weight' => $variant['weight'],
                    'variant_image' => $colorImageMap[$color],
                ]);
            }
        }

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
            'average_rating' => $averageRating, // Rata-rata rating produk
            'total_ratings' => $totalRatings,   // Total rating produk
        ]);
    }

    /**
     * Menampilkan form untuk mengedit produk.
     */
    public function edit(Product $product)
    {
        return view('dashboard.products.edit', [
            'title' => 'Edit Product',
            'product' => $product->load('variants'),
        ]);
    }

    /**
     * Memperbarui data produk yang sudah ada.
     */
    public function update(Request $request, Product $product)
    {
        // Validasi seperti biasa tapi sesuaikan patternnya
        $rules = [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|file|max:30720',
            'description' => 'nullable|string',
            'variants.*.*.color' => 'required|string|max:50',
            'variants.*.*.size' => 'required|string|max:10',
            'variants.*.*.price' => 'required|numeric|min:0',
            'variants.*.*.stock' => 'required|integer|min:0',
            'variants.*.*.weight' => 'required|numeric|min:0',
            'variants.*.*.variant_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        if ($request->slug != $product->slug) {
            $rules['slug'] = 'required|unique:products';
        }

        $validatedData = $request->validate($rules);

        // Update data produk
        if ($request->file('image')) {
            if ($product->image) {
                Storage::delete($product->image);
            }
            $validatedData['image'] = $request->file('image')->store('product-images');
        }

        $product->update([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'] ?? $product->slug,
            'image' => $validatedData['image'] ?? $product->image,
            'description' => $validatedData['description'] ?? null,
        ]);

        // --- Handle Variants ---
        $existingVariantIds = $product->variants->pluck('id')->toArray();
        $submittedVariantIds = [];

        if ($request->has('variants')) {
            $variantImages = $request->file('variant_images');

            foreach ($request->variants as $color => $variantGroup) {
                foreach ($variantGroup as $variantIndex => $variant) {
                    $variantId = $variant['id'] ?? null;

                    // Ambil file variant_image berdasarkan color dari $variantImages
                    $variantImagePath = null;
                    if (isset($variantImages[$color])) {
                        $variantImagePath = $variantImages[$color]->store('variant-images');
                    }

                    if ($variantId) {
                        // Update existing variant
                        $submittedVariantIds[] = $variantId;
                        $existing = $product->variants()->find($variantId);
                        if ($existing) {
                            $existing->update([
                                'color' => $variant['color'],
                                'size' => $variant['size'],
                                'price' => $variant['price'],
                                'stock' => $variant['stock'],
                                'weight' => $variant['weight'],
                                'variant_image' => $variantImagePath ?? $existing->variant_image,
                            ]);
                        }
                    } else {
                        // New variant
                        $newVariant = $product->variants()->create([
                            'color' => $variant['color'],
                            'size' => $variant['size'],
                            'price' => $variant['price'],
                            'stock' => $variant['stock'],
                            'weight' => $variant['weight'],
                            'variant_image' => $variantImagePath,
                        ]);
                        $submittedVariantIds[] = $newVariant->id;
                    }
                }
            }
        }

        // Hapus varian yang tidak dikirim (berarti dihapus dari form)
        $variantsToDelete = array_diff($existingVariantIds, $submittedVariantIds);
        foreach ($variantsToDelete as $variantId) {
            $variant = $product->variants()->find($variantId);

            if ($variant) {
                // Cek apakah ada varian lain dengan warna dan gambar yang sama
                $otherVariantsWithSameImage = $product->variants()
                    ->where('color', $variant->color)
                    ->where('variant_image', $variant->variant_image)
                    ->where('id', '!=', $variant->id)
                    ->exists();

                // Hapus gambar kalau nggak ada varian lain yang pakai
                if ($variant->variant_image && !$otherVariantsWithSameImage) {
                    Storage::delete($variant->variant_image);
                }

                $variant->delete();
            }
        }

        return redirect('/dashboard/products')->with('Success', 'Product updated successfully!');
    }

    /**
     * Menghapus produk yang dipilih.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::delete($product->image);
        }

        // Hapus semua gambar variant juga
        foreach ($product->variants as $variant) {
            if ($variant->variant_image) {
                Storage::delete($variant->variant_image);
            }
        }

        $product->delete();

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
