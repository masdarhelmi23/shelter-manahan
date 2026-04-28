<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function pay(Request $request) 
    {
        // Sekarang kamu bisa ambil data dari form mewah tadi
        $productId = $request->product_id;
        $amount = $request->amount;
        $phone = $request->phone;
        $notes = $request->notes;

        // Contoh logika sederhana:
        // Di sini kamu bisa masukkan ke tabel 'orders' atau lanjut ke Payment Gateway
        
        return "Terima kasih, pesanan produk ID $productId sedang diproses. Kami akan hubungi ke $phone";
    }

    public function create($id)
    {
        $product = Product::findOrFail($id);

        return view('checkout.create', compact('product'));
    }

    // =========================
    // PROSES BAYAR (OPSIONAL)
    // =========================
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'qty' => $request->qty ?? 1,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        $midtrans = new MidtransService();
        $snap = $midtrans->createTransaction($order);

        return redirect($snap->redirect_url);
    }
}