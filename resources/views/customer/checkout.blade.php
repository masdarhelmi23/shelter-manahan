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

    .container { width: 90%; max-width: 1000px; margin: 50px auto; padding-bottom: 100px; }
    
    /* CARD PUTIH SOLID (MATCHING WITH CART & ORDERS) */
    .checkout-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px; padding: 40px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .section-title { font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
    
    .item-list { border-bottom: 2px dashed #e2e8f0; margin-bottom: 25px; padding-bottom: 20px; }
    .item-row { display: flex; justify-content: space-between; margin-bottom: 15px; color: #475569; font-weight: 600; }
    
    .total-box { 
        background: #f8fafc; padding: 25px; border-radius: 15px; 
        display: flex; justify-content: space-between; align-items: center;
        border: 1px solid #e2e8f0;
    }
    .total-price { font-size: 28px; font-weight: 800; color: var(--primary); }

    .btn-pay {
        width: 100%; margin-top: 30px; padding: 18px; border-radius: 15px; border: none;
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #fff; font-weight: 800; font-size: 15px; cursor: pointer;
        transition: 0.3s; text-transform: uppercase; letter-spacing: 1px;
        display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-pay:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(2, 132, 199, 0.4); filter: brightness(1.1); }
</style>
@endsection

@section('content')
<div class="container">
    <div class="checkout-card">
        <h2 class="section-title"><i class="fa-solid fa-wallet"></i> Konfirmasi Pembayaran</h2>
        
        <div class="item-list">
            @foreach($cartItems as $item)
                <div class="item-row">
                    <span>{{ $item->product->nama_produk }} (x{{ $item->quantity }})</span>
                    <span>Rp {{ number_format($item->product->harga * $item->quantity, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="total-box">
            <span style="font-weight: 700; color: #64748b;">Total yang harus dibayar:</span>
            <span class="total-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>

        <button type="button" id="pay-button" class="btn-pay">
            BAYAR SEKARANG <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</div>

{{-- Script Midtrans Snap (Sandbox) --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function () {
        
        // 1. Tampilkan Efek Loading Executive
        Swal.fire({
            title: 'Menyiapkan Pembayaran...',
            text: 'Menghubungkan ke server Midtrans',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        // 2. Kirim Request ke Controller (AJAX)
        fetch("{{ route('checkout.pay') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                // Jika server ngirim error (misal code 500)
                throw new Error(data.error || 'Terjadi kesalahan sistem.');
            }
            return data;
        })
        .then(data => {
            Swal.close(); // Tutup loading setelah dapat token

            if (data.snap_token) {
                // 3. Eksekusi Jendela Popup Midtrans Snap
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            text: 'Membersihkan keranjang dan mengalihkan...',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => { 
                            // REVISI: Diarahkan ke rute finish untuk hapus cart
                            window.location.href = "{{ route('checkout.finish') }}"; 
                        });
                    },
                    onPending: function(result) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Menunggu Pembayaran',
                            text: 'Selesaikan pembayaranmu agar pesanan diproses.',
                        }).then(() => { 
                            // REVISI: Tetap diarahkan ke rute finish agar cart kosong setelah checkout dimulai
                            window.location.href = "{{ route('checkout.finish') }}"; 
                        });
                    },
                    onError: function(result) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat memproses pembayaran.', 'error');
                    },
                    onClose: function() {
                        Swal.fire('Dibatalkan', 'Kamu menutup jendela pembayaran sebelum selesai.', 'warning');
                    }
                });
            } else {
                Swal.fire('Error', 'Gagal mendapatkan tiket pembayaran dari Midtrans.', 'error');
            }
        })
        .catch(error => {
            Swal.close();
            console.error('Midtrans Error:', error);
            // Menampilkan pesan error spesifik agar Masdar tahu salahnya di mana
            Swal.fire({
                icon: 'error',
                title: 'Waduh, Ada Masalah',
                text: error.message,
                footer: '<span style="color: #ef4444">Pastikan Database dan Konfigurasi Midtrans Benar</span>'
            });
        });
    });
</script>
@endsection