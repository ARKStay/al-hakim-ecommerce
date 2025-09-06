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

    public function edit(User $user)
    {
        return view('dashboard.users.edit', [
            'title' => 'Edit User',
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']); // jangan update kalau kosong
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }


    /**
     * Toggle account status (Active / Inactive)
     */
    public function toggleStatus(User $user)
    {
        // Kalau statusnya Active, ubah ke Inactive
        // Kalau statusnya Inactive, ubah ke Active
        $user->account_status = $user->account_status === 'Active' ? 'Inactive' : 'Active';
        $user->save();

        return redirect('/dashboard/users')->with(
            'success',
            $user->account_status === 'Active'
                ? 'User account has been activated.'
                : 'User account has been deactivated.'
        );
    }

    public function activate(User $user)
    {
        $user->update(['account_status' => 'active']);
        return redirect()->back()->with('success', 'Account activated successfully.');
    }

    public function deactivate(User $user)
    {
        $user->update(['account_status' => 'inactive']);
        return redirect()->back()->with('success', 'Account deactivated successfully.');
    }
}
