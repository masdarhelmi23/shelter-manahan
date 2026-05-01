<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class CustomerController extends Controller
{
    public function showWarung($slug)
    {
        // Mencari toko berdasarkan slug yang unik (misal: warung-helmi)
        $shop = Shop::where('slug', $slug)->where('status', 'active')->firstOrFail();
        
        // Mengambil produk yang statusnya 'aktif' saja (yang tidak habis)
        $products = $shop->products()->where('status', 'aktif')->latest()->get();

        return view('customer.warung', compact('shop', 'products'));
    }

    public function checkout($id)
    {
        $product = Product::findOrFail($id);

        // contoh Midtrans (Snap)
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;

        $transaction = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $product->harga,
            ],
            'item_details' => [
                [
                    'id' => $product->id,
                    'price' => $product->harga,
                    'quantity' => 1,
                    'name' => $product->nama_produk,
                ]
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($transaction);

        return view('payment.checkout', compact('snapToken', 'product'));
    }

    public function cart()
    {
        // Mengambil item keranjang milik user yang sedang login
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        
        // Hitung total belanja
        $total = $cartItems->sum(function($item) {
            return $item->product->harga * $item->quantity;
        });

        return view('customer.cart', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request, $id)
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        // 2. Validasi input quantity
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            // 3. Cek apakah produk sudah ada di keranjang user tersebut
            $cartItem = Cart::where('user_id', Auth::id())
                            ->where('product_id', $id)
                            ->first();

            if ($cartItem) {
                // Jika sudah ada, update jumlahnya saja
                $cartItem->update([
                    'quantity' => $cartItem->quantity + $request->quantity
                ]);
            } else {
                // Jika belum ada, buat data baru di tabel carts
                Cart::create([
                    'user_id'    => Auth::id(),
                    'product_id' => $id,
                    'quantity'   => $request->quantity,
                ]);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Produk berhasil masuk keranjang!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeFromCart($id)
    {
        try {
            // Cari item keranjang milik user yang sedang login
            $cartItem = Cart::where('user_id', Auth::id())
                            ->where('id', $id)
                            ->firstOrFail();

            $cartItem->delete();

            return back()->with('success', 'Item berhasil dihapus dari keranjang.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus item.');
        }
    }
    public function orders()
    {
        // Ambil data, urutkan yang terbaru, lalu kelompokkan berdasarkan order_id
        $groupedOrders = Order::where('user_id', Auth::id())
                        ->with('product')
                        ->latest()
                        ->get()
                        ->groupBy('order_id');

        return view('customer.orders', compact('groupedOrders'));
    }
}