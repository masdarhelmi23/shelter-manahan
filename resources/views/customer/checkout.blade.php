@extends('layouts.customer')

@section('title', 'Pembayaran')

@section('extra-css')
<style>
    /* =========================================
       MODERN CULINARY AESTHETIC (GAMBAR JELAS)
    ========================================= */
    body {
        background: 
            linear-gradient(rgba(255, 255, 255, 0.50), rgba(255, 255, 255, 0.50)),
            url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2070&auto=format&fit=crop');
        background-size: cover; 
        background-position: center; 
        background-attachment: fixed;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
    }

    .container { width: 90%; max-width: 800px; margin: 50px auto; padding-bottom: 100px; }
    
    /* CARD PUTIH SOLID & EFEK GLASS */
    .checkout-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 24px; padding: 40px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.8);
    }

    .section-title { font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
    
    /* RINCIAN PESANAN STYLE */
    .item-list { border-bottom: 2px dashed #cbd5e1; margin-bottom: 25px; padding-bottom: 20px; }
    .item-row { display: flex; justify-content: space-between; margin-bottom: 15px; color: #475569; font-weight: 600; }
    .item-name { color: #0f172a; font-weight: 700; }
    .item-qty { font-size: 13px; color: #64748b; margin-left: 5px; }
    
    .total-box { 
        background: #f8fafc; padding: 25px; border-radius: 15px; 
        display: flex; justify-content: space-between; align-items: center;
        border: 1px solid #e2e8f0; margin-bottom: 30px;
    }
    .total-price { font-size: 28px; font-weight: 800; color: #ea580c; } 

    /* QRIS SECTION */
    .qris-wrapper {
        text-align: center; margin-bottom: 30px; padding: 20px;
        background: #f1f5f9; border-radius: 15px; border: 1px dashed #cbd5e1;
    }
    .qris-wrapper img {
        width: 100%; max-width: 250px; border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1); border: 3px solid #fff;
    }

    /* DOWNLOAD BUTTON */
    .btn-download {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        margin-top: 15px; padding: 10px 20px; border-radius: 10px;
        background: #e2e8f0; color: #0f172a; font-weight: 800; font-size: 12px;
        text-decoration: none; text-transform: uppercase; transition: 0.3s;
    }
    .btn-download:hover { background: #cbd5e1; transform: translateY(-2px); }

    /* BUTTONS */
    .btn-wa {
        width: 100%; padding: 18px; border-radius: 15px; border: none;
        background: #25D366; 
        color: #fff; font-weight: 800; font-size: 15px; cursor: pointer;
        transition: 0.3s; text-transform: uppercase; letter-spacing: 1px;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        text-decoration: none; box-shadow: 0 10px 20px rgba(37, 211, 102, 0.3);
    }
    .btn-wa:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(37, 211, 102, 0.4); filter: brightness(1.05); }

    .btn-back {
        width: 100%; padding: 15px; border-radius: 15px; border: 1px solid #cbd5e1;
        background: transparent; color: #475569; font-weight: 800; font-size: 13px; 
        cursor: pointer; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px;
        display: flex; align-items: center; justify-content: center; margin-top: 15px;
        text-decoration: none;
    }
    .btn-back:hover { background: #f1f5f9; color: #0f172a; }
    
    .invoice-badge {
        background: #e0f2fe; color: #0369a1; padding: 5px 15px; border-radius: 50px; font-size: 12px; font-weight: 800; margin-bottom: 20px; display: inline-block;
        border: 1px solid #bae6fd;
    }

    @media (max-width: 768px) {
        .checkout-card { padding: 25px; }
        .total-price { font-size: 24px; }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="checkout-card">
        <div class="invoice-badge">INVOICE: {{ $order->order_id }}</div>
        <h2 class="section-title"><i class="fa-solid fa-receipt"></i> Tagihan Pesanan</h2>
        
        <!-- DATA DIRI PEMESAN -->
        <div style="margin-bottom: 25px; font-size: 14px; color: #475569;">
            <p style="margin: 0;"><strong>Pemesan:</strong> {{ $order->customer_name }}</p>
            <p style="margin: 5px 0 0;"><strong>WhatsApp:</strong> {{ $order->customer_whatsapp }}</p>
            <p style="margin: 5px 0 0;"><strong>Toko:</strong> {{ $order->shop->name ?? 'Warung Shelter' }}</p>
        </div>

        <div class="item-list">
            <!-- MENGHITUNG TOTAL MURNI (TANPA BIAYA ADMIN) -->
            @php
                $pureTotal = 0;
            @endphp

            <!-- LOOPING DARI DETAIL PESANAN -->
            @foreach($order->details as $detail)
                @php
                    $pureTotal += $detail->subtotal;
                @endphp
                <div class="item-row">
                    <span>
                        <span class="item-name">{{ $detail->product->nama_produk }}</span>
                        <span class="item-qty">x{{ $detail->qty }}</span>
                    </span>
                    <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="total-box">
            <span style="font-weight: 700; color: #64748b;">Total Pembayaran:</span>
            <!-- MENAMPILKAN TOTAL MURNI -->
            <span class="total-price">Rp {{ number_format($pureTotal, 0, ',', '.') }}</span>
        </div>

        <!-- AREA QRIS / PEMBAYARAN -->
        @if($order->payment_method == 'midtrans')
            <!-- JIKA MEMILIH TRANSFER (QRIS) -->
            <div class="qris-wrapper">
                <h3 style="font-size: 16px; color: #0f172a; margin-bottom: 15px; font-weight: 800;">Scan QRIS Untuk Membayar</h3>
                
                @if(isset($order->shop) && $order->shop->qris_image)
                    <!-- Menampilkan Gambar QRIS -->
                    <img src="{{ asset('storage/' . $order->shop->qris_image) }}" alt="QRIS Warung">
                    <br>
                    
                    <!-- Tombol Download Gambar QRIS -->
                    <a href="{{ asset('storage/' . $order->shop->qris_image) }}" download="QRIS_{{ Str::slug($order->shop->name) }}.jpg" class="btn-download">
                        <i class="fa-solid fa-download"></i> SIMPAN QRIS
                    </a>
                    
                    <p style="font-size: 12px; color: #64748b; margin-top: 15px;">Pastikan nominal transfer sesuai dengan <b>Total Pembayaran</b>.</p>
                @else
                    <div style="padding: 20px; color: #dc2626; font-weight: 700;">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <p>Pemilik warung belum mengupload gambar QRIS.</p>
                    </div>
                @endif
            </div>

            <!-- TOMBOL WA -->
            @php
                $waNumber = $order->shop->whatsapp ?? '';
                if (str_starts_with($waNumber, '0')) {
                    $waNumber = '62' . substr($waNumber, 1);
                }
                
                $pesanWA = "Halo, saya ingin konfirmasi pembayaran untuk pesanan di *{$order->shop->name}*:%0A%0A";
                $pesanWA .= "*Invoice:* #{$order->order_id}%0A";
                $pesanWA .= "*Pemesan:* {$order->customer_name}%0A";
                // MENGGUNAKAN TOTAL MURNI UNTUK PESAN WA
                $pesanWA .= "*Total:* Rp " . number_format($pureTotal, 0, ',', '.') . "%0A%0A";
                $pesanWA .= "Berikut saya lampirkan bukti transfernya.";
                
                $waLink = "https://api.whatsapp.com/send?phone={$waNumber}&text={$pesanWA}";
            @endphp
            
            <a href="{{ $waLink }}" target="_blank" class="btn-wa">
                <i class="fa-brands fa-whatsapp fa-lg"></i> KONFIRMASI BUKTI TRANSFER
            </a>
        @else
            <!-- JIKA MEMILIH BAYAR DI KASIR -->
            <div style="background: #fff7ed; border: 1px solid #ffedd5; padding: 20px; border-radius: 15px; text-align: center; margin-bottom: 25px; color: #ea580c; font-weight: 700;">
                <i class="fa-solid fa-cash-register fa-2x mb-2"></i>
                <p style="margin:0;">Silakan tunjukkan halaman ini ke kasir warung saat melakukan pembayaran.</p>
            </div>
        @endif

        <a href="{{ route('orders.index') }}" class="btn-back">Lihat Riwayat Pesanan</a>
    </div>
</div>
@endsection