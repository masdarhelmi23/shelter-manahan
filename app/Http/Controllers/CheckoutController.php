<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
// Import Library Midtrans
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    /**
     * Konstruktor untuk inisialisasi konfigurasi Midtrans
     */
    public function __construct()
    {
        // Set Konfigurasi Midtrans dari .env
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * PROSES BAYAR (Generate Snap Token untuk Checkout All)
     */
    public function pay(Request $request) 
    {
        // 1. Ambil data keranjang user
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Keranjang kosong'], 400);
        }

        // 2. Hitung total harga
        $total = $cartItems->sum(function($item) {
            return $item->product->harga * $item->quantity;
        });

        try {
            // 3. Buat data di tabel orders
            // Menyesuaikan dengan kolom DB kamu: user_id, product_id, order_id, amount, status
            $order = Order::create([
                'user_id'    => Auth::id(),
                'product_id' => null, // Null karena checkout banyak produk (Cart)
                'order_id'   => 'INV-' . time() . '-' . Auth::id(), // Generate ID invoice unik
                'amount'     => (int)$total,
                'status'     => 'pending',
            ]);

            // 4. Siapkan parameter untuk Midtrans Snap
            $params = [
                'transaction_details' => [
                    'order_id'     => $order->order_id, // Menggunakan string order_id yang baru dibuat
                    'gross_amount' => (int)$total,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email'      => Auth::user()->email,
                ],
            ];

            // 5. Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // 6. Simpan snap_token ke database agar bisa digunakan nanti jika perlu
            $order->update(['snap_token' => $snapToken]);

            // 7. Kembalikan respon JSON ke JavaScript di Frontend
            return response()->json(['snap_token' => $snapToken]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * TAMPILAN CHECKOUT SATUAN (Buy Now)
     */
    public function create($id)
    {
        $product = Product::findOrFail($id);
        return view('checkout.create', compact('product'));
    }

    /**
     * TAMPILAN HALAMAN CHECKOUT (Summary dari Cart)
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong!');
        }

        $total = $cartItems->sum(function($item) {
            return $item->product->harga * $item->quantity;
        });

        // Diarahkan ke view checkout mewah kamu
        return view('customer.checkout', compact('cartItems', 'total'));
    }

    public function finish(Request $request)
    {
        // 1. Cari semua item di keranjang milik user yang login, lalu hapus bersih!
        \App\Models\Cart::where('user_id', \Illuminate\Support\Facades\Auth::id())->delete();

        // 2. Lempar user ke halaman Riwayat Pesanan dengan pesan sukses
        return redirect()->route('orders.index')->with('success', 'Pembayaran berhasil! Keranjang kamu sudah dikosongkan.');
    }
}