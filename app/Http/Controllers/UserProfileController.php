<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

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
     * Menampilkan form untuk mengedit profil pengguna.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        // Pastikan hanya user yg berhak yg bisa edit
        if (Auth::id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Ambil data provinsi
        $provinceResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'key' => config('rajaongkir.api_key'),
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

        $provinces = $provinceResponse->json('data') ?? [];

        // Ambil data city berdasarkan province_id user (kalau ada)
        $cities = [];
        if ($user->province_id) {
            $cityResponse = Http::withHeaders([
                'Accept' => 'application/json',
                'key' => config('rajaongkir.api_key'),
            ])->get("https://rajaongkir.komerce.id/api/v1/destination/city?province_id={$user->province_id}");

            $cities = $cityResponse->json('data') ?? [];
        }

        // Ambil data district berdasarkan city_id user (kalau ada)
        $districts = [];
        if ($user->city_id) {
            $districtResponse = Http::withHeaders([
                'Accept' => 'application/json',
                'key' => config('rajaongkir.api_key'),
            ])->get("https://rajaongkir.komerce.id/api/v1/destination/subdistrict?city_id={$user->city_id}");

            $districts = $districtResponse->json('data') ?? [];
        }

        return view('user.edit', [
            'user' => $user,
            'title' => 'Edit Profile',
            'provinces' => $provinces,
            'cities' => $cities,
            'districts' => $districts,
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
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:255|unique:users,username,' . $id,
            'email'         => 'required|string|email|max:255|unique:users,email,' . $id,
            'password'      => 'nullable|string|min:8|confirmed',
            'phone'         => 'nullable|string|max:15',
            'address'       => 'nullable|string|max:255',
            'province_id'   => 'nullable|string|max:20',
            'province_name' => 'nullable|string|max:255',
            'city_id'       => 'nullable|string|max:20',
            'city_name'     => 'nullable|string|max:255',
            'district_id'   => 'nullable|string|max:20',
            'district_name' => 'nullable|string|max:255',
        ]);

        // Perbarui data pengguna
        $user->name          = $request->input('name');
        $user->username      = $request->input('username');
        $user->email         = $request->input('email');
        $user->address       = $request->input('address');
        $user->province_id   = $request->input('province_id');
        $user->province_name = $request->input('province_name');
        $user->city_id       = $request->input('city_id');
        $user->city_name     = $request->input('city_name');
        $user->district_id   = $request->input('district_id');
        $user->district_name = $request->input('district_name');

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Normalisasi nomor HP sebelum disimpan
        if ($request->filled('phone')) {
            $user->phone = $this->normalizePhoneNumber($request->input('phone'));
        }

        $user->save();

        // Redirect setelah profil berhasil diperbarui
        return redirect()->route('profile.index')
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

    /**
     * Helper untuk normalisasi nomor HP.
     */
    private function normalizePhoneNumber($phone)
    {
        // Hilangin spasi, strip, titik biar bersih
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // Kalau diawali dengan 0 → ganti jadi 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Kalau diawali dengan +62 → ganti jadi 62
        if (substr($phone, 0, 3) === '+62') {
            $phone = '62' . substr($phone, 3);
        }

        return $phone;
    }
}
