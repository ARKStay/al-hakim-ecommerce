<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna.
     */
    public function index()
    {
        return view('user.profile', [
            'title' => 'My Profile',
        ]);
    }

    /**
     * Menampilkan form untuk membuat resource baru.
     */
    public function create()
    {
        //
    }

    /**
     * Menyimpan resource baru ke dalam penyimpanan.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Menampilkan resource tertentu.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Menampilkan form untuk mengedit profil pengguna.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        // Pastikan hanya pengguna yang berwenang dapat mengedit
        if (Auth::id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.edit', [
            'user' => $user,
            'title' => 'Edit Profile',
        ]);
    }

    /**
     * Memperbarui profil pengguna di penyimpanan.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Pastikan hanya pengguna yang berwenang dapat mengupdate
        if (Auth::id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi input yang diterima dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed', // Validasi password hanya jika ada
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        // Perbarui data pengguna
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        // Redirect setelah profil berhasil diperbarui
        return redirect()->route('profile.index', ['user' => $user->id])
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Menghapus akun pengguna dan melakukan logout.
     */
    public function destroy($id)
    {
        // Ambil data user berdasarkan ID
        $user = User::findOrFail($id);

        // Hapus user
        $user->delete();

        // Logout pengguna
        Auth::logout();

        // Redirect ke halaman login dengan pesan sukses
        return redirect('/login')->with('success', 'Your account has been deleted. Please login again.');
    }
}
