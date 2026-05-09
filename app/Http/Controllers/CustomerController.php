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
use Illuminate\Support\Str; // Tambahkan ini

class CustomerController extends Controller
{
    public function index()
    {
        $groupedOrders = collect(); 

        if (Auth::check()) {
            $groupedOrders = Order::with(['details.product', 'shop'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(5) 
                ->get();
        }

        $shops = \App\Models\Shop::where('status', 'active')->get();

        return view('welcome', compact('groupedOrders', 'shops'));
    }

    public function profile() { return view('customer.profile'); }

    public function showWarung($id) 
    {
        $shop = Shop::where('id', $id)
                    ->where('status', 'active')
                    ->firstOrFail();

        $products = $shop->products()->where('status', 'aktif')->get();

        return view('customer.warung', compact('shop', 'products'));
    }

    public function orderCheckout($id)
    {
        $order = Order::where('order_id', $id)
                    ->orWhere('id', $id)
                    ->with('details.product')
                    ->firstOrFail();

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = $order->amount;

        if (!$order->snap_token && $order->payment_method === 'midtrans') {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_id,
                    'gross_amount' => (int) $order->amount,
                ],
                'customer_details' => [
                    'first_name' => $order->customer_name,
                    'phone' => $order->customer_whatsapp,
                ],
                'callbacks' => [
                    'finish' => env('APP_URL') . '/orders', 
                    'notification' => env('APP_URL') . '/api/midtrans/callback', 
                ]
            ];

            $order->snap_token = \Midtrans\Snap::getSnapToken($params);
            $order->save();
        }

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
                $target_shop_id = null; // Variabel penampung ID Warung

                foreach ($request->items as $id => $item) {
                    if ($item['qty'] > 0) {
                        $product = Product::findOrFail($id);
                        
                        // AMBIL SHOP_ID dari produk pertama yang ditemukan
                        if (!$target_shop_id) {
                            $target_shop_id = $product->shop_id;
                        }

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
                $invoice_id = 'MOTO-' . strtoupper(Str::random(10)); 

                // PERBAIKAN: Masukkan shop_id ke sini
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'shop_id' => $target_shop_id, // SEKARANG SUDAH ADA ISI-NYA
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
                        ->with(['details.product', 'shop']) // Tambahkan .shop di sini agar riwayat tidak error
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

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $order = Order::where('order_id', $request->order_id)->first();

                if ($order && $order->status == 'pending') {
                    $order->update(['status' => 'settlement']);

                    foreach ($order->details as $detail) {
                        $product = $detail->product;
                        if ($product && $product->shop) {
                            $shop = $product->shop;
                            $subtotal = $detail->qty * $detail->price;
                            $shop->increment('balance', $subtotal);
                        }
                    }
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}