<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;


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

        // 1. Kunjungan Katalog
        $kunjungan = $shop->views ?? 0;

        // 2. Pendapatan Real-Time
        $totalPendapatan = Order::whereHas('details.product', function($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->whereIn('status', ['settlement', 'success'])->sum('amount');

        // 3. Total Transaksi Real-Time
        $totalPesanan = Order::whereHas('details.product', function($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->count();

        // 4. Menu Terlaris
        $menuPopuler = Product::where('shop_id', $shop->id)
            ->withCount(['details as orders_count' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->whereIn('status', ['settlement', 'success']);
                });
            }])
            ->orderBy('orders_count', 'desc')
            ->first();

        if ($menuPopuler) {
            $menuPopuler->total_sold = $menuPopuler->orders_count;
        }

        $totalOrderWA = $produk->sum('clicks');

        return view('owner.dashboard', compact(
            'shop', 'produk', 'kunjungan', 'menuPopuler', 
            'totalOrderWA', 'totalPendapatan', 'totalPesanan'
        ));
    }

    /**
     * JALUR KHUSUS: Mengatur Durasi Libur Warung
     * REVISI: Penambahan type-casting (int) untuk mencegah error pada PHP 8.4
     */
    public function setLibur(Request $request)
    {
        $shop = Auth::user()->shop;

        if ($request->action == 'buka') {
            $shop->closed_until = null;
            $shop->is_active = true;
            $shop->save();
            return redirect()->back()->with('success', 'Warung kembali mengikuti jam operasional normal.');
        }

        $request->validate(['durasi' => 'required|numeric|min:0']);

        // Pastikan durasi diubah menjadi integer sebelum diproses Carbon
        $durasiHari = (int) $request->durasi;

        // Set tutup sampai akhir hari sesuai durasi yang dipilih
        $shop->closed_until = Carbon::now()->addDays($durasiHari)->endOfDay();
        $shop->is_active = false; // Mematikan status aktif selama masa libur
        $shop->save();

        return redirect()->back()->with('success', 'Status warung berhasil diatur libur selama ' . $durasiHari . ' hari.');
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

    public function create()
    {
        $shop = Auth::user()->shop;
        return view('owner.produk_tambah', compact('shop'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'status' => 'required|in:aktif,tidak aktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $shop = Auth::user()->shop;
        if (!$shop) return back()->with('error', 'Toko tidak ditemukan!');

        $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('produk', 'public') : null;

        Product::create([
            'shop_id' => $shop->id,
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'status' => $request->status,
            'foto' => $fotoPath,
        ]);
        return redirect()->route('owner.produk')->with('success', 'Produk berhasil masuk katalog!');
    }

    public function edit($id)
    {
        $shop = Auth::user()->shop;
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();
        return view('owner.produk_edit', compact('shop', 'product'));
    }

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
        $data = ['nama_produk' => $request->nama_produk, 'harga' => $request->harga, 'status' => $request->status];
        if ($request->hasFile('foto')) {
            if ($product->foto) Storage::disk('public')->delete($product->foto);
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }
        $product->update($data);
        return redirect()->route('owner.produk')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $shop = Auth::user()->shop;
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();
        if ($product->foto) Storage::disk('public')->delete($product->foto);
        $product->delete();
        return redirect()->route('owner.produk')->with('success', 'Produk berhasil dihapus!');
    }

    public function storeToko(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'whatsapp' => 'nullable|string|max:20', 'instagram' => 'nullable|string|max:255', 'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048']);
        $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('logos', 'public') : null;
        Shop::create(['name' => $request->name, 'whatsapp' => $request->whatsapp, 'instagram' => $request->instagram, 'logo' => $logoPath, 'user_id' => Auth::id(), 'open_time' => '08:00', 'close_time' => '22:00', 'is_active' => true, 'status' => 'active']);
        return redirect()->back()->with('success', 'Toko berhasil dibuat!');
    }

    public function updateStatusToko(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $request->validate(['status' => 'required|in:active,pending']);
        $shop->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status Toko diperbarui!');
    }

    public function pengaturan()
    {
        $shop = Auth::user()->shop;
        $user = Auth::user();
        return view('owner.pengaturan', compact('shop', 'user'));
    }

    public function updatePengaturan(Request $request)
    {
        $user = Auth::user();
        $shop = $user->shop;
        if (!$shop) return back()->with('error', 'Toko tidak ditemukan');

        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'username'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'logo'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $shop->name = $request->nama_toko;
        $shop->whatsapp = $request->whatsapp;
        if ($request->filled('jam_buka')) $shop->open_time = $request->jam_buka;
        if ($request->filled('jam_tutup')) $shop->close_time = $request->jam_tutup;

        if ($request->hasFile('logo')) {
            if ($shop->logo) Storage::disk('public')->delete($shop->logo);
            $shop->logo = $request->file('logo')->store('logos', 'public');
        }
        $shop->save();

        $user->name = $request->username;
        $user->email = $request->email;
        if ($request->password) $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    public function pesanan()
    {
        $shop = Auth::user()->shop;
        if (!$shop) return redirect()->back()->with('error', 'Toko tidak ditemukan.');
        $orders = Order::whereHas('details.product', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->with(['details.product', 'user'])->latest()->get();
        return view('owner.pesanan', compact('orders', 'shop'));
    }

    public function storePesanan(Request $request)
    {
        $request->validate(['customer_name' => 'required|string', 'customer_whatsapp' => 'required', 'payment_method' => 'required', 'amount' => 'required|numeric', 'status' => 'required', 'items' => 'required|array']);
        $order = Order::create(['order_id' => 'MOTO-' . strtoupper(Str::random(10)), 'customer_name' => $request->customer_name, 'customer_whatsapp' => $request->customer_whatsapp, 'payment_method' => $request->payment_method, 'amount' => $request->amount, 'status' => $request->status, 'user_id' => Auth::id()]);
        foreach ($request->items as $item) {
            if (isset($item['product_id']) && $item['qty'] > 0) {
                $product = Product::find($item['product_id']);
                OrderDetail::create(['order_id' => $order->id, 'product_id' => $item['product_id'], 'qty' => $item['qty'], 'subtotal' => $product->harga * $item['qty']]);
            }
        }
        return redirect()->back()->with('success', 'Pesanan manual berhasil dibuat!');
    }

    public function updateStatusPesanan(Request $request, $id)
    {
        Order::findOrFail($id)->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function updatePesanan(Request $request, $id)
    {
        $request->validate(['amount' => 'required|numeric', 'status' => 'required|in:pending,success,settlement']);
        Order::findOrFail($id)->update(['amount' => $request->amount, 'status' => $request->status]);
        return redirect()->back()->with('success', 'Data pesanan diperbarui!');
    }

    public function destroyPesanan($id)
    {
        Order::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Pesanan dihapus!');
    }
    // Tambahkan fungsi withdraw di dalam OwnerController
    public function withdraw(Request $request)
    {
        $shop = auth()->user()->shop;

        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'bank_info' => 'required|string',
        ]);

        // Tetap cek apakah saldo cukup sebelum kirim permintaan
        if ($shop->balance < $request->amount) {
            return redirect()->back()->with('error', 'Saldo Anda tidak mencukupi.');
        }

        // Buat data di tabel withdrawals dengan status 'pending'
        // Saldo TIDAK dipotong di sini
        Withdrawal::create([
            'shop_id' => $shop->id,
            'amount' => $request->amount,
            'bank_info' => $request->bank_info,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Permintaan penarikan telah dikirim ke Admin.');
    }

    public function indexWithdraw()
    {
        $shop = Auth::user()->shop;
        
        // Ambil riwayat WD milik toko ini
        $withdrawals = Withdrawal::where('shop_id', $shop->id)
                        ->latest()
                        ->get();

        return view('owner.withdraw', compact('shop', 'withdrawals'));
    }
}