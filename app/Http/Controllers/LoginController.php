<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;


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
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/dashboard')->with('success', 'Welcome back, ' . $user->username . '!');
            } else {
                return redirect()->intended('/user')->with('success', 'Welcome, ' . $user->username . '!');
            }
        }

        // Jika login gagal, kembali ke halaman login dengan pesan error
        return back()->with('loginError', 'Login failed!');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm($token)
    {
        $email = DB::table('password_reset_tokens')->where('token', $token)->value('email');

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
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
