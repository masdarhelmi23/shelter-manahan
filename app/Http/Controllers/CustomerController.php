<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

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
}