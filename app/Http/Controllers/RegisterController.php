<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Menampilkan form pendaftaran.
     */
    public function index(){
        return view('register.index', [
            'title' => 'Register',
            'active' => 'register'
        ]);
    }

    /**
     * Menyimpan data pendaftaran pengguna baru.
     */
    public function store(Request $request){
        // Validasi data input dari form pendaftaran
        $validateData = $request->validate([
            'name' => 'required|max:225',
            'username' => ['required', 'min:3', 'max:225', 'unique:users'],
            'email' => ['required', 'email:dns', 'unique:users'],
            'password' => ['required', 'min:5', 'max:255']
        ]);

        // Enkripsi password sebelum disimpan
        $validateData['password'] = Hash::make($validateData['password']);

        // Simpan data pengguna baru ke dalam database
        User::create($validateData);

        // Redirect ke halaman login dengan pesan sukses
        return redirect('/login')->with('success', 'Registration successfull! Please login');
    }
}
