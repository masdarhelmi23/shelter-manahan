<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail; // Tambahkan Model ini
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan facade DB untuk transaksi

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

    /**
     * FUNGSI STORE REVISI: Menangani Pesanan Multi-Item & Pembayaran
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_whatsapp' => 'required|string|max:20',
            'payment_method' => 'required|in:cashier,midtrans',
            'items' => 'required|array'
        ]);

        return DB::transaction(function () use ($request) {
            $subtotal = 0;
            $admin_fee = ($request->payment_method === 'midtrans') ? 2500 : 0; // Biaya Admin
            $orderItems = [];

            // 2. Hitung Total & Siapkan Data Item
            foreach ($request->items as $id => $item) {
                if ($item['qty'] > 0) {
                    $product = Product::findOrFail($id);
                    $line_total = $product->harga * $item['qty'];
                    $subtotal += $line_total;

                    $orderItems[] = [
                        'product_id' => $id,
                        'qty' => $item['qty'],
                        'price' => $product->harga,
                        'subtotal' => $line_total,
                    ];
                }
            }

            $grand_total = $subtotal + $admin_fee;

            // 3. Simpan ke Tabel 'orders' (Master)
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_id' => 'INV-' . strtoupper(uniqid()),
                'customer_name' => $request->customer_name,
                'customer_whatsapp' => $request->customer_whatsapp,
                'payment_method' => $request->payment_method,
                'amount' => $grand_total,
                'admin_fee' => $admin_fee,
                'status' => 'pending',
            ]);

            // 4. Simpan ke Tabel 'order_details' (Detail)
            foreach ($orderItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // 5. Integrasi Midtrans (Jika dipilih)
            $paymentUrl = null;
            if ($request->payment_method === 'midtrans') {
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = false;

                $transaction_details = [
                    'order_id' => $order->order_id,
                    'gross_amount' => $grand_total,
                ];

                $snapToken = \Midtrans\Snap::getSnapToken(['transaction_details' => $transaction_details]);
                // Kamu bisa mengarahkan ke halaman checkout yang sudah ada
                $paymentUrl = route('payment.checkout', ['id' => $orderItems[0]['product_id'], 'token' => $snapToken]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'payment_url' => $paymentUrl
            ]);
        });
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