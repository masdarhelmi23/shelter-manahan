<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller {
    
    public function dashboard() {
        $totalUser = User::count();
        $totalToko = Shop::count();
        return view('admin.dashboard', compact('totalUser', 'totalToko'));
    }

    // List Pengguna
    public function users() {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    // Tambah Pengguna Baru
    public function storeUser(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * UPDATE PENGGUNA (Method ini sekarang bernama 'update' sesuai permintaan Route)
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
            'password' => 'nullable|min:3'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    /**
     * HAPUS PENGGUNA (Method ini sekarang bernama 'delete' sesuai permintaan Route)
     */
    public function delete($id) {
        $user = User::findOrFail($id);
        
        if (Auth::id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }

        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus!');
    }

    // List Toko
    public function shops() {
        $shops = Shop::with('user')->get();
        return view('admin.shops', compact('shops'));
    }

    // Approve Toko
    public function approveShop(Request $request, $id) 
    {
        $shop = Shop::findOrFail($id);
        
        // Ambil status dari dropdown form
        $shop->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status operasional toko berhasil diperbarui!');
    }
    public function index() {
    // Ambil semua toko yang statusnya 'active'
    $shops = \App\Models\Shop::where('status', 'active')->get();
    return view('welcome', compact('shops'));
}
public function deleteUser($id)
{
    // Ambil data user berdasarkan ID
    $user = \App\Models\User::findOrFail($id);
    
    // Opsional: Cek apakah user punya toko, kalau ada hapus juga atau cegah hapus
    if($user->shop) {
        // Misalnya kita hapus fotonya dulu jika ada
        if($user->shop->logo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->shop->logo);
        }
        $user->shop->delete();
    }

    // Hapus user
    $user->delete();

    return redirect()->back()->with('success', 'User berhasil dihapus selamanya!');
}
}