@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('extra-css')
<style>
    /* =========================================
       PREMIUM DARK AESTHETIC (SERASI DENGAN WARUNG)
    ========================================= */
    body {
        background: 
            radial-gradient(circle at top right, rgba(2, 132, 199, 0.15), transparent),
            linear-gradient(to bottom, rgba(0, 0, 0, 0.92), rgba(15, 23, 42, 0.95)),
            url('https://www.transparenttextures.com/patterns/dark-matter.png'),
            url('{{ asset("images/bg-shelter.jpg") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .container { width: 90%; max-width: 1200px; margin: 50px auto; padding-bottom: 100px; }

    /* HEADER PAGE */
    .page-header { margin-bottom: 40px; text-align: left; border-left: 5px solid var(--primary); padding-left: 20px; }
    .page-header h1 { color: #fff; font-size: 32px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
    .page-header p { color: var(--teks-abu); font-size: 14px; }

    /* GLASSMORPHISM TABLE CONTAINER */
    .cart-glass-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px;
        padding: 35px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .cart-table { width: 100%; border-collapse: collapse; }
    .cart-table th { 
        text-align: left; padding: 15px; color: #94a3b8; font-size: 11px; 
        text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 2px solid #f1f5f9; 
    }
    .cart-table td { padding: 25px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

    /* ITEM STYLING */
    .prod-flex { display: flex; align-items: center; gap: 20px; }
    .prod-img { width: 85px; height: 85px; border-radius: 18px; object-fit: cover; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .prod-details h3 { font-size: 18px; font-weight: 800; color: var(--teks-gelap); margin-bottom: 4px; }
    .prod-details p { font-size: 13px; color: var(--teks-abu); }

    .price-main { font-weight: 800; color: var(--primary); font-size: 16px; }

    /* QTY CONTROL (SAME AS WARUNG) */
    .qty-control {
        display: flex; align-items: center; background: #f1f5f9; 
        border-radius: 12px; padding: 5px; width: fit-content;
    }
    .btn-qty {
        width: 32px; height: 32px; border: none; background: #fff;
        color: var(--teks-gelap); border-radius: 8px; cursor: pointer;
        font-weight: 800; transition: 0.2s;
    }
    .btn-qty:hover { background: var(--primary); color: #fff; }
    .input-qty { width: 45px; border: none; background: transparent; text-align: center; font-weight: 800; font-size: 15px; }

    /* SUMMARY & CHECKOUT */
    .cart-footer {
        margin-top: 40px; display: flex; justify-content: space-between; align-items: center;
        padding: 30px; background: #f8fafc; border-radius: 20px; border: 1px solid #e2e8f0;
    }
    .total-label { color: #64748b; font-size: 14px; font-weight: 600; margin-bottom: 5px; }
    .total-amount { font-size: 28px; font-weight: 800; color: #0f172a; }

    .btn-checkout {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #fff; padding: 18px 45px; border-radius: 16px; text-decoration: none;
        font-weight: 800; font-size: 14px; transition: 0.3s;
        display: flex; align-items: center; gap: 12px; text-transform: uppercase;
    }
    .btn-checkout:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(2, 132, 199, 0.4); }

    .btn-remove { color: #ef4444; background: none; border: none; cursor: pointer; font-size: 18px; transition: 0.2s; }
    .btn-remove:hover { transform: scale(1.2); color: #b91c1c; }

    @media (max-width: 768px) {
        .cart-footer { flex-direction: column; gap: 20px; text-align: center; }
        .cart-table th:nth-child(2), .cart-table td:nth-child(2) { display: none; }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Keranjang Saya</h1>
        <p>Kelola pesanan lezatmu sebelum melakukan pembayaran</p>
    </div>

    <div class="cart-glass-card">
        @if($cartItems->count() > 0)
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th style="text-align: center;">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr>
                            <td>
                                <div class="prod-flex">
                                    <img src="{{ asset('storage/' . $item->product->foto) }}" class="prod-img">
                                    <div class="prod-details">
                                        <h3>{{ $item->product->nama_produk }}</h3>
                                        <p>ID Produk: #{{ $item->product->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="price-main">Rp {{ number_format($item->product->harga, 0, ',', '.') }}</span></td>
                            <td>
                                <div class="qty-control">
                                    <button class="btn-qty" onclick="changeCartQty({{ $item->id }}, -1)">-</button>
                                    <input type="number" class="input-qty" value="{{ $item->quantity }}" readonly>
                                    <button class="btn-qty" onclick="changeCartQty({{ $item->id }}, 1)">+</button>
                                </div>
                            </td>
                            <td><span class="price-main">Rp {{ number_format($item->product->harga * $item->quantity, 0, ',', '.') }}</span></td>
                            <td style="text-align: center;">
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-remove" title="Hapus Item">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="cart-footer">
                <div>
                    <p class="total-label">Estimasi Total Pembayaran:</p>
                    <h2 class="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</h2>
                </div>
                <a href="{{ route('checkout.all') }}" class="btn-checkout">
                    Proses Checkout <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fa-solid fa-cart-arrow-down fa-5x" style="color: #e2e8f0; margin-bottom: 25px;"></i>
                <h2 style="color: #0f172a; margin-bottom: 10px;">Keranjang Masih Kosong</h2>
                <p style="color: #64748b; margin-bottom: 30px;">Sepertinya kamu belum memilih makanan enak hari ini.</p>
                <a href="/" class="btn-checkout" style="display: inline-flex;">Mulai Cari Makan</a>
            </div>
        @endif
    </div>
</div>

<script>
    function changeCartQty(cartId, delta) {
        // Logika update quantity via AJAX akan kita pasang di sini nanti
        // Untuk sekarang kita simulasi refresh atau loading...
        Swal.fire({
            title: 'Memperbarui...',
            timer: 500,
            didOpen: () => { Swal.showLoading() }
        }).then(() => {
            // Logika redirect atau update tampilan
            location.reload(); 
        });
    }
</script>
@endsection