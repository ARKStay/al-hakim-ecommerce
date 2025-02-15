<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class DashboardCategoriesController extends Controller
{
    /**
     * Menampilkan daftar kategori berdasarkan filter pencarian.
     */
    public function index(Request $request)
    {
        // Mengambil kategori yang difilter berdasarkan pencarian
        $categories = Category::filter($request->only('search'))->get();
        
        // Mengirimkan data kategori ke tampilan
        return view('dashboard.categories.index', ['title' => 'Category List', 'categories' => $categories]);
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        // Menampilkan form untuk menambahkan kategori baru
        return view('dashboard.Categories.create', [
            'title' => 'Add Category',
            'categories' => Category::all() // Menampilkan semua kategori
        ]);
    }

    /**
     * Menyimpan kategori baru yang diterima dari form.
     */
    public function store(Request $request)
    {
        // Validasi input untuk memastikan nama kategori unik
        $validateData = $request->validate([
            'name' => 'required|max:20|unique:categories,name', // Validasi nama kategori
        ]);

        // Menyimpan kategori baru ke database
        Category::create($validateData);

        // Mengarahkan kembali dengan pesan sukses
        return redirect('/dashboard/categories')->with('Success', 'New category has been added!');
    }

    /**
     * Menampilkan detail kategori berdasarkan ID (kosong untuk sementara).
     */
    public function show(string $id)
    {
        // Tidak ada implementasi untuk method ini
    }

    /**
     * Menampilkan form untuk mengedit kategori yang ada.
     */
    public function edit(Category $category)
    {
        // Menampilkan form edit kategori dengan data kategori yang ada
        return view('dashboard.Categories.edit', [
            'title' => 'Edit Category',
            'category' => $category, // Data kategori yang diedit
            'categories' => Category::all() // Menampilkan semua kategori
        ]);
    }

    /**
     * Memperbarui data kategori yang sudah ada berdasarkan ID.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input nama kategori baru untuk memastikan unik
        $request->validate([
            'name' => 'required|max:20|unique:categories,name,' . $id, // Memastikan nama kategori unik
        ]);

        // Mencari kategori berdasarkan ID dan memperbarui namanya
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        // Mengarahkan kembali dengan pesan sukses
        return redirect('/dashboard/categories')->with('Success', 'Category updated successfully!');
    }

    /**
     * Menghapus kategori yang dipilih.
     */
    public function destroy(Category $category)
    {
        // Menghapus kategori dari database
        $category->delete();

        // Mengarahkan kembali dengan pesan sukses
        return redirect('/dashboard/categories')->with('Success', 'Category has been deleted!');
    }
}
