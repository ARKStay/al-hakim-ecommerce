<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardBannersController extends Controller
{
    /**
     * Menampilkan daftar banner.
     */
    public function index()
    {
        // Mengambil semua data banner dan mengirimkan ke tampilan
        return view('dashboard.banners.index', ['title' => 'Banners', 'banners' => Banner::all()]);
    }

    /**
     * Menampilkan form untuk menambahkan banner baru.
     */
    public function create()
    {
        // Menampilkan form untuk menambahkan banner
        return view('dashboard.Banners.create', [
            'title' => 'Add Banner'
        ]);
    }

    /**
     * Menyimpan banner baru yang diterima dari form.
     */
    public function store(Request $request)
    {
        // Validasi input gambar dan status banner
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:30720', // Validasi gambar
            'status' => 'required|string|max:3', // Validasi status
        ]);

        // Jika ada gambar yang diunggah, simpan di folder 'product-banners'
        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('product-banners');
        }

        // Menyimpan data banner ke database
        Banner::create($validatedData);

        // Mengarahkan kembali dengan pesan sukses
        return redirect('/dashboard/banners')->with('Success', 'New banner has been added!');
    }

    /**
     * Menampilkan detail banner berdasarkan ID.
     */
    public function show(Banner $banner)
    {
        // Menampilkan detail banner yang dipilih
        return view('dashboard.Banners.show', [
            'banner' => $banner,
            'title' => 'Banner'
        ]);
    }

    /**
     * Menampilkan form untuk mengedit banner yang dipilih.
     */
    public function edit(Banner $banner)
    {
        // Menampilkan form edit untuk banner yang dipilih
        return view('dashboard.Banners.edit', [
            'title' => 'Edit Banner',
            'banner' => $banner
        ]);
    }

    /**
     * Memperbarui data banner yang sudah ada.
     */
    public function update(Request $request, Banner $banner)
    {
        // Aturan validasi untuk gambar dan status banner
        $rules = [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:30720', // Validasi gambar
            'status' => 'required|string|max:3', // Validasi status
        ];

        // Validasi input yang diterima
        $validatedData = $request->validate($rules);

        // Jika ada gambar baru, hapus gambar lama dan simpan gambar baru
        if ($request->file('image')) {
            if ($request->oldImage) {
                // Menghapus gambar lama dari storage
                Storage::delete($request->oldImage);
            }
            // Menyimpan gambar baru ke folder 'product-banners'
            $validatedData['image'] = $request->file('image')->store('product-banners');
        }

        // Memperbarui data banner dengan data yang sudah divalidasi
        $banner->update($validatedData);

        // Mengarahkan kembali dengan pesan sukses
        return redirect('/dashboard/banners')->with('Success2', 'Banner updated successfully!');
    }

    /**
     * Menghapus banner yang dipilih.
     */
    public function destroy(Banner $banner)
    {
        // Jika banner memiliki gambar, hapus gambar dari storage
        if ($banner->image) {
            Storage::delete($banner->image);
        }

        // Menghapus banner dari database
        $banner->delete();

        // Mengarahkan kembali dengan pesan sukses
        return redirect('/dashboard/banners')->with('Success', 'Banner has been deleted!');
    }
}
