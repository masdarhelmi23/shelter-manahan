<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shop;
use App\Models\Withdrawal; // Tambahan model Withdrawal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahan Facade DB
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller {
    
    public function dashboard()
    {
        // 1. Hitung total user
        $totalUser = User::count();

        // 2. Hitung total toko aktif
        $totalToko = Shop::where('status', 'active')->count();

        // 3. HITUNG TOTAL SALDO (Penting agar tidak error)
        $totalSaldo = Shop::sum('balance'); 

        // 4. Ambil pengajuan penarikan dana yang masih pending
        $pendingWithdrawals = Withdrawal::with('shop')
                                ->where('status', 'pending')
                                ->latest()
                                ->take(5)
                                ->get();

        // 5. Kirim SEMUA variabel ke view
        return view('admin.dashboard', compact(
            'totalUser', 
            'totalToko', 
            'totalSaldo', 
            'pendingWithdrawals'
        ));
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
     * UPDATE PENGGUNA
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
     * HAPUS PENGGUNA
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
        
        $shop->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status operasional toko berhasil diperbarui!');
    }

    public function index() {
        $shops = Shop::where('status', 'active')->get();
        return view('welcome', compact('shops'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if($user->shop) {
            if($user->shop->logo) {
                Storage::disk('public')->delete($user->shop->logo);
            }
            $user->shop->delete();
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus selamanya!');
    }

    // --- MANAJEMEN PENARIKAN DANA (WITHDRAWAL) ---

    // Tampilkan daftar semua permintaan penarikan
    public function withdrawals()
    {
        $withdrawals = Withdrawal::with('shop')->latest()->get();
        return view('admin.withdrawals', compact('withdrawals'));
    }

    // Approve Penarikan & Potong Saldo
    public function approveWithdraw($id)
    {
        $withdraw = Withdrawal::findOrFail($id);

        if ($withdraw->status !== 'pending') {
            return redirect()->back()->with('error', 'Permintaan ini sudah diproses.');
        }

        // Gunakan transaksi database untuk memastikan saldo hanya terpotong jika status berubah
        DB::transaction(function () use ($withdraw) {
            // 1. Potong saldo Toko
            $withdraw->shop->decrement('balance', $withdraw->amount);

            // 2. Update status penarikan jadi sukses
            $withdraw->update([
                'status' => 'success',
                'transferred_at' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Dana berhasil dipotong dan status diperbarui.');
    }

    // Tolak Penarikan
    public function rejectWithdraw($id)
    {
        $withdraw = Withdrawal::findOrFail($id);

        if ($withdraw->status !== 'pending') {
            return redirect()->back()->with('error', 'Permintaan ini sudah diproses.');
        }

        $withdraw->update([
            'status' => 'rejected'
        ]);

        return redirect()->back()->with('success', 'Permintaan penarikan telah ditolak.');
    }


    public function showShop($id)
    {
        // Mengambil data toko beserta user dan riwayat penarikannya
        $shop = Shop::with(['user', 'withdrawals' => function($query) {
            $query->latest();
        }])->findOrFail($id);

        return view('admin.shop_detail', compact('shop'));
    }
}