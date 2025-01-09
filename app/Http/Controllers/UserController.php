<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Ambil semua data user
        $users = User::all();

        // Return view dengan data user
        return view('admin.user', compact('users'));
    }

    public function edit($id)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Return view untuk edit user
        return view('admin.edit-user', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:15',
            'alamat' => 'nullable|string|max:255',
            'role' => 'required|in:admin,user',
        ]);

        // Update data user
        $user = User::findOrFail($id);
        $user->update($request->all());

        return redirect()->route('manajemen-user')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Hapus user berdasarkan ID
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('manajemen-user')->with('success', 'Data user berhasil dihapus.');
    }
}
