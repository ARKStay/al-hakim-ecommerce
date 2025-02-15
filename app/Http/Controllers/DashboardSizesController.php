<?php

namespace App\Http\Controllers;

use App\Models\Sizes;
use Illuminate\Http\Request;

class DashboardSizesController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data ukuran berdasarkan filter pencarian
        $sizes = Sizes::filter($request->only('search'))->get();
        return view('dashboard.sizes.index', ['title' => 'Size List', 'sizes' => $sizes]);
    }

    public function create()
    {
        return view('dashboard.Sizes.create', [
            'title' => 'Add Size',
            'sizes' => Sizes::all() // Mengambil semua ukuran yang ada
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data input dari form
        $validateData = $request->validate([
            'name' => 'required|max:8|unique:sizes,name', // Validasi nama ukuran
        ]);

        // Menyimpan ukuran baru ke dalam database
        Sizes::create($validateData);

        return redirect('/dashboard/sizes')->with('Success', 'New size has been added!');
    }

    public function show(string $id)
    {
        // Tidak ada implementasi untuk fungsi ini
    }

    public function edit(Sizes $size)
    {
        return view('dashboard.Sizes.edit', [
            'title' => 'Edit Size',
            'size' => $size, // Mengirimkan data ukuran yang akan diedit
            'sizes' => Sizes::all() // Mengambil semua ukuran yang ada
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Validasi input form untuk memperbarui nama ukuran
        $request->validate([
            'name' => 'required|max:20|unique:sizes,name,' . $id, // Validasi nama ukuran
        ]);

        $size = Sizes::findOrFail($id);
        $size->update([
            'name' => $request->name,
        ]);

        return redirect('/dashboard/sizes')->with('Success', 'Size updated successfully!');
    }

    public function destroy(Sizes $size)
    {
        // Menghapus ukuran dari database
        $size->delete();

        return redirect('/dashboard/sizes')->with('Success', 'Size has been deleted!');
    }
}
