@extends('layouts.customer')

@section('title', 'Riwayat Pesanan')

@section('extra-css')
<style>
    /* =========================================
       PREMIUM DARK AESTHETIC (SERASI DENGAN CART)
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
    .page-header p { color: #94a3b8; font-size: 14px; }

    /* WHITE CONTAINER (SERASI DENGAN CART-GLASS-CARD) */
    .orders-white-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px;
        padding: 35px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .orders-table { width: 100%; border-collapse: collapse; }
    .orders-table th { 
        text-align: left; padding: 15px; color: #94a3b8; font-size: 11px; 
        text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 2px solid #f1f5f9; 
    }
    .orders-table td { padding: 25px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

    /* INFO STYLING */
    .order-id { font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
    .order-date { font-size: 13px; color: #64748b; }

    .price-main { font-weight: 800; color: var(--primary); font-size: 16px; }

    /* STATUS BADGES */
    .status-badge {
        padding: 8px 16px; border-radius: 50px; font-size: 10px; font-weight: 800; 
        text-transform: uppercase; display: inline-block;
    }
    .status-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .status-completed { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }

    /* BUTTON ACTION */
    .btn-detail {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #fff; padding: 12px 25px; border-radius: 12px; text-decoration: none;
        font-weight: 800; font-size: 12px; transition: 0.3s;
        display: inline-flex; align-items: center; gap: 8px; text-transform: uppercase;
    }
    .btn-detail:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(2, 132, 199, 0.3); }

    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }

    @media (max-width: 768px) {
        .orders-table th:nth-child(2), .orders-table td:nth-child(2) { display: none; }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Riwayat Pesanan</h1>
        <p>Pantau status transaksi kuliner kamu di Shelter Manahan</p>
    </div>

    <div class="orders-white-card">
        @if($orders->count() > 0)
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Invoice / Tanggal</th>
                        <th>Status</th>
                        <th>Total Bayar</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <div class="order-id">#{{ $order->id }}{{ $order->created_at->format('mdY') }}</div>
                                <div class="order-date">
                                    <i class="fa-regular fa-calendar"></i> {{ $order->created_at->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $order->status == 'pending' ? 'status-pending' : 'status-completed' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td><span class="price-main">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span></td>
                            <td style="text-align: center;">
                                <a href="#" class="btn-detail">
                                    Detail <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-receipt fa-5x" style="color: #e2e8f0; margin-bottom: 25px;"></i>
                <h2 style="color: #0f172a; margin-bottom: 10px;">Belum Ada Pesanan</h2>
                <p style="color: #64748b; margin-bottom: 30px;">Kamu belum pernah melakukan transaksi nih.</p>
                <a href="/" class="btn-detail">Mulai Pesan Sekarang</a>
            </div>
        @endif
    </div>
</div>
@endsection