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
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) return response()->json(['error' => 'Kosong'], 400);

        $total = $cartItems->sum(fn($item) => $item->product->harga * $item->quantity);
        
        // Buat satu ID Invoice yang sama untuk semua barang ini
        $invoiceId = 'INV-' . time() . '-' . Auth::id();

        try {
            // SIMPAN TIAP BARANG SEBAGAI SATU BARIS
            foreach ($cartItems as $cart) {
                Order::create([
                    'user_id'    => Auth::id(),
                    'product_id' => $cart->product_id,
                    'order_id'   => $invoiceId, // ID-nya sama semua
                    'amount'     => $cart->product->harga * $cart->quantity, // Harga per barang x qty
                    'status'     => 'pending',
                ]);
            }

            // Konfigurasi Midtrans (Gross Amount tetap TOTAL semua barang)
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = false;
            
            $params = [
                'transaction_details' => [
                    'order_id'     => $invoiceId,
                    'gross_amount' => (int)$total,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email'      => Auth::user()->email,
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Update snap_token di semua baris yang invoice-nya sama
            Order::where('order_id', $invoiceId)->update(['snap_token' => $snapToken]);

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