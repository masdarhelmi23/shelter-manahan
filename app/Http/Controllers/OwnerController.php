<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $shop = auth()->user()->shop;

        // Inisialisasi variabel agar tidak error null
        $produk = $shop ? $shop->products : collect([]);

        // 1. Kunjungan Katalog (Data dari tabel shops)
        $kunjungan = $shop->views ?? 0;

        // 2. Popularitas Menu (Mencari produk dengan clicks terbanyak)
        $menuPopuler = $produk->sortByDesc('clicks')->first();

        // 3. Order WA (Total akumulasi klik WA dari semua produk)
        $totalOrderWA = $produk->sum('clicks');

        return view('owner.dashboard', compact('shop', 'produk', 'kunjungan', 'menuPopuler', 'totalOrderWA'));
    }

    /**
     * Menampilkan daftar produk
     */
    public function produk()
    {
        $shop = Auth::user()->shop;
        // Ambil produk terbaru milik toko ini
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
        // Pastikan owner hanya bisa edit produk miliknya sendiri
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        return view('owner.produk_edit', compact('shop', 'product'));
    }

    /**
     * Update data produk
     */
    public function update(Request $request, $id)
    {
        $shop = Auth::user()->shop;
        // Proteksi: hanya bisa update produk milik sendiri
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
        // Proteksi: hanya bisa hapus produk milik sendiri
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }
        $product->delete();

        return redirect()->route('owner.produk')->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Simpan Toko baru
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
        ]);

        return redirect()->back()->with('success', 'Toko berhasil dibuat!');
    }

    public function updateStatusToko(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $request->validate([
            'status' => 'required|in:active,pending'
        ]);

        $shop->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status Toko ' . $shop->name . ' berhasil diperbarui!');
    }

    // Tampilkan Halaman Pengaturan
    public function pengaturan()
    {
        $shop = Auth::user()->shop;
        $user = Auth::user();
        return view('owner.pengaturan', compact('shop', 'user'));
    }

    // Proses Update Pengaturan
    public function updatePengaturan(Request $request)
    {
        $user = Auth::user();
        $shop = $user->shop;

        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'logo'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'whatsapp'  => 'nullable|string',
            'instagram' => 'nullable|string',
        ]);

        $dataShop = [
            'name'      => $request->nama_toko,
            'slug'      => Str::slug($request->nama_toko), // Update slug kalau nama berubah
            'whatsapp'  => $request->whatsapp,
            'instagram' => $request->instagram,
        ];

        if ($request->hasFile('logo')) {
            if ($shop->logo && Storage::disk('public')->exists($shop->logo)) {
                Storage::disk('public')->delete($shop->logo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            $dataShop['logo'] = $path;
        }

        $shop->update($dataShop);

        return redirect()->back()->with('success', 'Berhasil update!');
    }
}
