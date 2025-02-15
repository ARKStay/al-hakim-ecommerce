<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardUsersController extends Controller
{
    /**
     * Menampilkan daftar pengguna berdasarkan filter pencarian.
     */
    public function index(Request $request)
    {
        $users = User::filter($request->only('search'))->get();
        return view('dashboard.users.index', [
            'title' => 'User List',
            'users' => $users,
        ]);
    }

    /**
     * Menghapus pengguna yang dipilih.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/dashboard/users')->with('success', 'User deleted successfully.');
    }
}
