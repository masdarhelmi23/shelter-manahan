@extends('layouts.customer')

@section('title', 'Pembayaran')

@section('extra-css')
<style>
    /* =========================================
       EXECUTIVE DARK BACKGROUND & WHITE CARD
    ========================================= */
    body {
        background: 
            radial-gradient(circle at top right, rgba(2, 132, 199, 0.15), transparent),
            linear-gradient(to bottom, rgba(0, 0, 0, 0.92), rgba(15, 23, 42, 0.95)),
            url('https://www.transparenttextures.com/patterns/dark-matter.png'),
            url('{{ asset("images/bg-shelter.jpg") }}');
        background-size: cover; background-attachment: fixed;
    }

    .container { width: 90%; max-width: 800px; margin: 50px auto; padding-bottom: 100px; }
    
    /* CARD PUTIH SOLID */
    .checkout-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px; padding: 40px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .section-title { font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
    
    /* RINCIAN PESANAN STYLE */
    .item-list { border-bottom: 2px dashed #e2e8f0; margin-bottom: 25px; padding-bottom: 20px; }
    .item-row { display: flex; justify-content: space-between; margin-bottom: 15px; color: #475569; font-weight: 600; }
    .item-name { color: #0f172a; font-weight: 700; }
    .item-qty { font-size: 13px; color: #64748b; margin-left: 5px; }
    
    .total-box { 
        background: #f8fafc; padding: 25px; border-radius: 15px; 
        display: flex; justify-content: space-between; align-items: center;
        border: 1px solid #e2e8f0;
    }
    .total-price { font-size: 28px; font-weight: 800; color: #0284c7; }

    .btn-pay {
        width: 100%; margin-top: 30px; padding: 18px; border-radius: 15px; border: none;
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #fff; font-weight: 800; font-size: 15px; cursor: pointer;
        transition: 0.3s; text-transform: uppercase; letter-spacing: 1px;
        display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-pay:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(2, 132, 199, 0.4); filter: brightness(1.1); }
    
    .invoice-badge {
        background: #e0f2fe; color: #0369a1; padding: 5px 15px; border-radius: 50px; font-size: 12px; font-weight: 800; margin-bottom: 20px; display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="checkout-card">
        <div class="invoice-badge">INVOICE: {{ $order->order_id }}</div>
        <h2 class="section-title"><i class="fa-solid fa-receipt"></i> Rincian Pesanan</h2>
        
        <!-- DATA DIRI PEMESAN -->
        <div style="margin-bottom: 25px; font-size: 14px; color: #475569;">
            <p style="margin: 0;"><strong>Pemesan:</strong> {{ $order->customer_name }}</p>
            <p style="margin: 5px 0 0;"><strong>WhatsApp:</strong> {{ $order->customer_whatsapp }}</p>
        </div>

        <div class="item-list">
            <!-- LOOPING DARI DETAIL PESANAN (MASTER-DETAIL) -->
            @foreach($order->details as $detail)
                <div class="item-row">
                    <span>
                        <span class="item-name">{{ $detail->product->nama_produk }}</span>
                        <span class="item-qty">x{{ $detail->qty }}</span>
                    </span>
                    <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach

            <!-- BIAYA ADMIN JIKA ADA -->
            @if($order->admin_fee > 0)
                <div class="item-row" style="color: #0369a1;">
                    <span>Biaya Layanan Midtrans</span>
                    <span>Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</span>
                </div>
            @endif
        </div>

        <div class="total-box">
            <span style="font-weight: 700; color: #64748b;">Total Akhir:</span>
            <span class="total-price">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
        </div>

        <button type="button" id="pay-button" class="btn-pay">
            BAYAR SEKARANG <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</div>

{{-- Script Midtrans Snap --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    const snapToken = "{{ $snapToken }}"; // Ambil token dari controller

    // Langsung pemicu Midtrans saat tombol diklik
    payButton.addEventListener('click', function () {
        if (!snapToken || snapToken === "") {
            Swal.fire({
                icon: 'error',
                title: 'Token Kosong',
                text: 'Gagal memuat tiket pembayaran. Silakan buat pesanan ulang.',
            });
            return;
        }

        window.snap.pay(snapToken, {
            onSuccess: function(result) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil!',
                    text: 'Mengalihkan ke riwayat pesanan...',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => { 
                    window.location.href = "{{ route('orders.index') }}"; 
                });
            },
            onPending: function(result) {
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu Pembayaran',
                    text: 'Silakan selesaikan pembayaran sesuai instruksi Midtrans.',
                }).then(() => { 
                    window.location.href = "{{ route('orders.index') }}"; 
                });
            },
            onError: function(result) {
                Swal.fire('Gagal!', 'Pembayaran dibatalkan atau bermasalah.', 'error');
            },
            onClose: function() {
                Swal.fire('Dibatalkan', 'Selesaikan pembayaran untuk memproses pesanan.', 'warning');
            }
        });
    });
</script>
@endsection