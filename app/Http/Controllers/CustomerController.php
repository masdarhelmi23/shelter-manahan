<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index() { return view('customer.home'); }
    public function profile() { return view('customer.profile'); }

    public function showWarung($slug)
    {
        $shop = Shop::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $products = $shop->products()->where('status', 'aktif')->latest()->get();
        
        // Data keranjang untuk navbar
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        
        return view('customer.warung', compact('shop', 'products', 'cartItems'));
    }

    /**
     * Menangani Halaman Checkout Pesanan
     * Perbaikan: Mengganti $invoice_id menjadi $order->order_id 
     * dan $grand_total menjadi $order->amount
     */
    public function orderCheckout($id)
    {
        // 1. Cari data pesanan berdasarkan Invoice atau ID
        $order = Order::where('order_id', $id)
                    ->orWhere('id', $id)
                    ->with('details.product')
                    ->firstOrFail();

        // 2. Ambil data keranjang (untuk Navbar)
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        // 3. Definisikan variabel $total agar tidak error di view
        $total = $order->amount;

        // 4. Logika Midtrans SNAP
        if (!$order->snap_token && $order->payment_method === 'midtrans') {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_id, // FIX: Ambil dari objek $order
                    'gross_amount' => (int) $order->amount, // FIX: Ambil dari objek $order
                ],
                'customer_details' => [
                    'first_name' => $order->customer_name,
                    'phone' => $order->customer_whatsapp,
                ],
                // Tetap menggunakan Dynamic Callback agar lancar di 2 Ngrok berbeda
                'callbacks' => [
                    'finish' => env('APP_URL') . '/orders', 
                    'notification' => env('APP_URL') . '/api/midtrans/callback', 
                ]
            ];

            $order->snap_token = \Midtrans\Snap::getSnapToken($params);
            $order->save();
        }

        // 5. Kirim semua variabel yang dibutuhkan view
        return view('customer.checkout', [
            'order' => $order,
            'snapToken' => $order->snap_token,
            'cartItems' => $cartItems,
            'total' => $total 
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_whatsapp' => 'required|string|max:20',
            'payment_method' => 'required|in:cashier,midtrans',
            'items' => 'required|array'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $subtotal = 0;
                $admin_fee = ($request->payment_method === 'midtrans') ? 2500 : 0;
                $orderItemsData = [];

                foreach ($request->items as $id => $item) {
                    if ($item['qty'] > 0) {
                        $product = Product::findOrFail($id);
                        $line_total = $product->harga * $item['qty'];
                        $subtotal += $line_total;
                        $orderItemsData[] = [
                            'product_id' => $id,
                            'qty' => $item['qty'],
                            'price' => $product->harga,
                            'subtotal' => $line_total,
                        ];
                    }
                }

                $grand_total = $subtotal + $admin_fee;
                $invoice_id = 'MOTO-' . strtoupper(uniqid()); // Menggunakan prefix untuk keamanan multi-project

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_id' => $invoice_id,
                    'customer_name' => $request->customer_name,
                    'customer_whatsapp' => $request->customer_whatsapp,
                    'payment_method' => $request->payment_method,
                    'amount' => $grand_total,
                    'admin_fee' => $admin_fee,
                    'status' => 'pending',
                ]);

                foreach ($orderItemsData as $item) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }

                // Opsional: Langsung buat snap_token jika user memilih midtrans
                if ($request->payment_method === 'midtrans') {
                    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                    \Midtrans\Config::$isProduction = false;
                    \Midtrans\Config::$isSanitized = true;
                    \Midtrans\Config::$is3ds = true;

                    $params = [
                        'transaction_details' => [
                            'order_id' => $invoice_id,
                            'gross_amount' => (int) $grand_total,
                        ],
                        'customer_details' => [
                            'first_name' => $request->customer_name,
                            'phone' => $request->customer_whatsapp,
                        ],
                        'callbacks' => [
                            'finish' => env('APP_URL') . '/orders',
                            'notification' => env('APP_URL') . '/api/midtrans/callback',
                        ]
                    ];
                    
                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                    $order->update(['snap_token' => $snapToken]);
                }

                return response()->json([
                    'success' => true,
                    'order_id' => $order->order_id,
                    'payment_method' => $request->payment_method
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function orders()
    {
        $groupedOrders = Order::where('user_id', Auth::id())
                        ->with('details.product') 
                        ->latest()
                        ->get();
        
        $cartItems = Cart::where('user_id', Auth::id())->get();
        
        return view('customer.orders', compact('groupedOrders', 'cartItems'));
    }

    public function cart()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = $cartItems->sum(fn($item) => $item->product->harga * $item->quantity);
        return view('customer.cart', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request, $id)
    {
        if (!Auth::check()) return response()->json(['success' => false], 401);
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $id)->first();
        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $request->quantity]);
        } else {
            Cart::create(['user_id' => Auth::id(), 'product_id' => $id, 'quantity' => $request->quantity]);
        }
        return response()->json(['success' => true]);
    }

    public function removeFromCart($id)
    {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return back();
    }

    /**
     * Webhook Callback dari Midtrans
     */
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                
                // Cari order berdasarkan order_id dari Midtrans
                $order = Order::where('order_id', $request->order_id)->first();

                // Pastikan order ditemukan dan statusnya masih pending sebelum diupdate
                if ($order && $order->status == 'pending') {
                    
                    // 1. Update status pesanan menjadi settlement (LUNAS)
                    $order->update(['status' => 'settlement']);

                    // 2. Loop semua detail pesanan untuk menambah saldo ke masing-masing toko
                    // Ini penting jika dalam satu invoice ada lebih dari satu toko
                    foreach ($order->details as $detail) {
                        $product = $detail->product;
                        if ($product && $product->shop) {
                            // Ambil toko pemilik produk
                            $shop = $product->shop;
                            
                            // Hitung total harga produk ini (qty x harga)
                            $subtotal = $detail->qty * $detail->price;

                            // Tambahkan ke saldo toko tersebut
                            $shop->increment('balance', $subtotal);
                        }
                    }
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}