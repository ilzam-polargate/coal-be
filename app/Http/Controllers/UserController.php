<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan semua user
    public function index()
    {
        $users = User::all();  // Mengambil semua user dari database
        return view('pages.users', compact('users'));  // Kirim ke view users.index
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        // Validasi dan simpan user
        $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|email|unique:users',
            'hp' => 'required|string',
            'password' => 'required|string|min:8|confirmed',  // Password minimal 8 karakter
            'position' => 'required|string',
        ]);

        // Convert position and access_level to lowercase
        $position = strtolower($request->position);
        $access_level = $position === 'director' ? strtolower($request->access_level) : null;

        try {
            User::create([
                'username' => $request->username,
                'email' => $request->email,
                'hp' => $request->hp,
                'password' => Hash::make($request->password),
                'position' => $position,
                'access_level' => $access_level,
                'user_status' => 'active',  // Set default status to active
            ]);

            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Gagal menambahkan user!');
        }
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'hp' => 'required|string',
            'position' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',  // Validasi password baru (optional) dan konfirmasi password
        ]);
    
        // Convert position and access_level to lowercase
        $position = strtolower($request->position);
        $access_level = $position === 'director' ? strtolower($request->access_level) : null;
    
        try {
            $data = [
                'username' => $request->username,
                'email' => $request->email,
                'hp' => $request->hp,
                'position' => $position,
                'access_level' => $access_level,
                'user_status' => $request->user_status,
            ];
    
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
    
            $user->update($data);
    
            return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Gagal mengupdate user!');
        }
    }
    
    

    public function destroy(User $user)
    {
        try {
            $user->delete();

            // Set flash message for success
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            // Set flash message for error
            return redirect()->route('users.index')->with('error', 'Gagal menghapus user!');
        }
    }

}
