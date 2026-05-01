<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order; // Wajib diimport untuk data transaksi real-time
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; // Import Hash untuk keamanan password
use Illuminate\Support\Str;

class OwnerController extends Controller
{
    /**
     * Dashboard dengan data Real-Time
     */
    public function dashboard()
    {
        $shop = Auth::user()->shop;

        if (!$shop) {
            return view('owner.dashboard', compact('shop'));
        }

        $produk = $shop->products;

        // 1. Kunjungan Katalog (Data views dari tabel shops)
        $kunjungan = $shop->views ?? 0;

        // 2. Pendapatan Real-Time (Total lunas dari tabel orders)
        $totalPendapatan = Order::whereHas('product', function($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->where('status', 'success')->sum('amount');

        // 3. Total Transaksi Real-Time
        $totalPesanan = Order::whereHas('product', function($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->count();

        // 4. Menu Terlaris (Berdasarkan jumlah transaksi sukses)
        $menuPopuler = Product::where('shop_id', $shop->id)
            ->withCount(['orders' => function($query) {
                $query->where('status', 'success');
            }])
            ->orderBy('orders_count', 'desc')
            ->first();

        // Mapping agar variabel di view tetap kompatibel
        if ($menuPopuler) {
            $menuPopuler->total_sold = $menuPopuler->orders_count;
        }

        // Variabel lama tetap dikirim agar tidak error jika view belum diupdate
        $totalOrderWA = $produk->sum('clicks');

        return view('owner.dashboard', compact(
            'shop', 'produk', 'kunjungan', 'menuPopuler', 
            'totalOrderWA', 'totalPendapatan', 'totalPesanan'
        ));
    }

    /**
     * Menampilkan daftar produk
     */
    public function produk()
    {
        $shop = Auth::user()->shop;
        $produk = $shop ? $shop->products()->latest()->get() : collect();

        return view('owner.produk', compact('shop', 'produk'));
    }

    /** 
     * Tampilkan form tambah produk
     */
    public function create()
    {
        $shop = Auth::user()->shop;
        return view('owner.produk_tambah', compact('shop'));
    }

    /**
     * Simpan produk baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'status' => 'required|in:aktif,tidak aktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $shop = Auth::user()->shop;

        if (!$shop) {
            return back()->with('error', 'Toko tidak ditemukan! Silakan buat toko dulu di Dashboard.');
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('produk', 'public');
        }

        Product::create([
            'shop_id' => $shop->id,
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'status' => $request->status,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('owner.produk')->with('success', 'Produk berhasil masuk katalog!');
    }

    /**
     * Tampilkan form edit produk
     */
    public function edit($id)
    {
        $shop = Auth::user()->shop;
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        return view('owner.produk_edit', compact('shop', 'product'));
    }

    /**
     * Update data produk
     */
    public function update(Request $request, $id)
    {
        $shop = Auth::user()->shop;
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'status' => 'required|in:aktif,tidak aktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'status' => $request->status,
        ];

        if ($request->hasFile('foto')) {
            if ($product->foto) {
                Storage::disk('public')->delete($product->foto);
            }
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $product->update($data);

        return redirect()->route('owner.produk')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Hapus produk
     */
    public function destroy($id)
    {
        $shop = Auth::user()->shop;
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }
        $product->delete();

        return redirect()->route('owner.produk')->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Simpan Toko baru (Dilengkapi default jam agar tidak NULL)
     */
    public function storeToko(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        Shop::create([
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'instagram' => $request->instagram,
            'logo' => $logoPath,
            'user_id' => Auth::id(),
            'open_time' => '08:00', // Default jam buka
            'close_time' => '22:00', // Default jam tutup
            'is_active' => true,    // Default aktif
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Toko berhasil dibuat!');
    }

    /**
     * Update status verifikasi toko (Admin/Internal)
     */
    public function updateStatusToko(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $request->validate(['status' => 'required|in:active,pending']);
        $shop->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status Toko ' . $shop->name . ' berhasil diperbarui!');
    }

    /**
     * Tampilkan Halaman Pengaturan
     */
    public function pengaturan()
    {
        $shop = Auth::user()->shop;
        $user = Auth::user();
        return view('owner.pengaturan', compact('shop', 'user'));
    }

    /**
     * Proses Update Pengaturan Profil & Toko
     */
    public function updatePengaturan(Request $request)
    {
        $user = Auth::user();
        $shop = $user->shop;

        if (!$shop) return back()->with('error', 'Toko tidak ditemukan');

        // 1. Validasi (Pastikan input di form divalidasi)
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'whatsapp'  => 'nullable|string',
            'instagram' => 'nullable|string',
            'jam_buka'  => 'nullable',
            'jam_tutup' => 'nullable',
            'username'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'logo'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'password'  => 'nullable|min:8|confirmed',
        ]);

        // 2. Update Data Toko secara Eksplisit
        $shop->name = $request->nama_toko;
        $shop->whatsapp = $request->whatsapp;
        $shop->instagram = $request->instagram;
        
        if ($request->filled('jam_buka')) {
            $shop->open_time = $request->jam_buka; 
        }
        if ($request->filled('jam_tutup')) {
            $shop->close_time = $request->jam_tutup; 
        }

        // 3. Proses Logo
        if ($request->hasFile('logo')) {
            if ($shop->logo && Storage::disk('public')->exists($shop->logo)) {
                Storage::disk('public')->delete($shop->logo);
            }
            $shop->logo = $request->file('logo')->store('logos', 'public');
        }
        
        $shop->save();

        // 4. Update Akun User
        $user->name = $request->username;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->back()->with('success', 'Profil dan pengaturan toko berhasil diperbarui!');
    }

    /**
     * Tampilkan Halaman Pesanan
     */
    public function pesanan()
    {
        $shop = Auth::user()->shop;
        if (!$shop) return redirect()->route('owner.dashboard');

        $orders = Order::whereHas('product', function($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->with('product')->latest()->get();

        return view('owner.pesanan', compact('shop', 'orders'));
    }

    /**
     * Simpan Pesanan Manual (Modal Tambah)
     */
    public function storePesanan(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|numeric',
            'status' => 'required|in:pending,success'
        ]);

        Order::create([
            'product_id' => $request->product_id,
            'amount' => $request->amount,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Pesanan manual berhasil dibuat!');
    }

    /**
     * Update Status Pesanan Cepat
     */
    public function updateStatusPesanan(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * Update Data Pesanan (Modal Edit)
     */
    public function updatePesanan(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'status' => 'required|in:pending,success'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'amount' => $request->amount,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data pesanan berhasil diperbarui!');
    }

    /**
     * Hapus Pesanan
     */
    public function destroyPesanan($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->back()->with('success', 'Pesanan berhasil dihapus!');
    }
}