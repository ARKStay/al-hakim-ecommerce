<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function index()
    {
        return view('login.index', [
            'title' => 'Login',
            'active' => 'login'
        ]);
    }

    /**
     * Melakukan autentikasi pengguna.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required'
        ]);

        // Hapus semua session yang ada untuk memastikan tidak ada session lama yang terbawa
        session()->invalidate();

        // Regenerasi CSRF token untuk keamanan
        session()->regenerateToken();

        // Coba untuk melakukan autentikasi dengan kredensial yang diberikan
        if (Auth::attempt($credentials)) {
            // Regenerasi session setelah login berhasil untuk menghindari session fixation
            $request->session()->regenerate();

            // Cek peran user setelah session baru di-set
            if (Auth::user()->role === 'admin') {
                // Jika user adalah admin, arahkan ke dashboard
                return redirect()->intended('/dashboard');
            } else {
                // Jika user biasa, arahkan ke halaman user
                return redirect()->intended('/user');
            }
        }

        // Jika login gagal, kembali ke halaman login dengan pesan error
        return back()->with('loginError', 'Login failed!');
    }

    /**
     * Melakukan logout dan mengalihkan ke halaman utama.
     */
    public function logout()
    {
        // Proses logout
        Auth::logout();

        // Invalidate session untuk menghapus data yang tersisa
        request()->session()->invalidate();

        // Regenerasi token CSRF untuk keamanan
        request()->session()->regenerateToken();

        // Redirect ke halaman utama setelah logout
        return redirect('/')->with('success', 'You have successfully logged out.');
    }
}
