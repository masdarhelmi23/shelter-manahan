@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* TYPOGRAPHY & LAYOUT */
    .judul-halaman { font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 5px; }
    .subjudul-halaman { font-size: 13px; color: #fcd34d; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; margin-bottom: 30px; display: block; }
    
    .header-aksi { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; }

    .kartu-mewah {
        background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px);
        border-radius: 25px; padding: 30px; border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* TABEL STYLE (Desktop & Tablet) */
    .tabel-pesanan { width: 100%; border-collapse: separate; border-spacing: 0 15px; }
    .tabel-pesanan th { color: #fcd34d; text-align: left; padding: 10px 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
    .tabel-pesanan td { background: rgba(255, 255, 255, 0.05); color: #fff; padding: 20px; vertical-align: middle; }
    .tabel-pesanan tr td:first-child { border-radius: 15px 0 0 15px; }
    .tabel-pesanan tr td:last-child { border-radius: 0 15px 15px 0; }

    /* STATUS & BUTTONS */
    .badge-status { padding: 6px 12px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; display: inline-block; }
    .status-pending { background: rgba(252, 211, 77, 0.2); color: #fcd34d; }
    .status-lunas { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
    .status-error { background: rgba(220, 38, 38, 0.2); color: #f87171; }
    
    .btn-aksi { padding: 10px 15px; border-radius: 12px; border: none; font-size: 11px; font-weight: 800; cursor: pointer; transition: 0.3s; color: #fff; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
    .btn-tambah { background: #0284c7; box-shadow: 0 10px 20px rgba(2, 132, 199, 0.3); font-size: 14px; padding: 15px 25px; }
    .btn-update { background: #16a34a; }
    .btn-hapus { background: #dc2626; }

    /* ================= RESPONSIVE MOBILE REVISION ================= */
    @media (max-width: 768px) {
        .header-aksi { flex-direction: column; align-items: center; text-align: center; }
        .btn-tambah { width: 100%; justify-content: center; }

        /* Transform Tabel ke Kartu */
        .tabel-pesanan thead { display: none; }
        .tabel-pesanan, .tabel-pesanan tbody, .tabel-pesanan tr, .tabel-pesanan td { display: block; width: 100%; }
        .tabel-pesanan tr { margin-bottom: 20px; border-radius: 20px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.1); }
        .tabel-pesanan td { 
            display: flex; justify-content: space-between; align-items: center; 
            padding: 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); text-align: right;
        }
        .tabel-pesanan td::before { content: attr(data-label); font-weight: 800; color: #fcd34d; font-size: 10px; text-align: left; text-transform: uppercase; }
    }
</style>

<div class="header-aksi">
    <div>
        <div class="judul-halaman">Manajemen Pesanan</div>
        <span class="subjudul-halaman">Daftar Transaksi - {{ $shop->name }}</span>
    </div>
    <!-- LINK KE PAGE BARU -->
    <a href="{{ route('owner.pesanan.create') }}" class="btn-aksi btn-tambah">
        <i class="fas fa-plus"></i> Buat Pesanan Baru
    </a>
</div>

@if(session('success'))
<div style="background: rgba(34, 197, 94, 0.2); color: #4ade80; padding: 15px; border-radius: 15px; margin-bottom: 20px; border: 1px solid rgba(34, 197, 94, 0.3);">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="kartu-mewah">
    <div class="table-responsive">
        <table class="tabel-pesanan">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Pemesan</th>
                    <th>Total</th>
                    <th>Metode Pembayaran</th>
                    <th>Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td data-label="Invoice">
                        <div style="font-weight: 800; color: #fcd34d;">#{{ $order->order_id }}</div>
                        <div style="font-size: 11px; color: #94a3b8;">{{ $order->created_at->format('d/m/y H:i') }}</div>
                    </td>
                    <td data-label="Pemesan">
                        <div style="font-weight: 700;">{{ $order->customer_name ?? 'Umum' }}</div>
                        <div style="font-size: 11px; color: #4ade80;">{{ $order->customer_whatsapp ?? '-' }}</div>
                    </td>
                    <td data-label="Total">
                        <div style="font-weight: 900; color: #fff; font-size: 16px;">Rp{{ number_format($order->amount, 0, ',', '.') }}</div>
                    </td>
                    <td data-label="Metode">
                        <div style="font-size: 11px; color: #cbd5e1; font-weight: 700; text-transform: uppercase;">
                            {{ $order->payment_method == 'midtrans' ? 'Transfer Bank' : 'Bayar di Kasir' }}
                        </div>
                    </td>
                    <td data-label="Status">
                        @php
                            $statusClass = ($order->status == 'pending') ? 'status-pending' : (($order->status == 'success' || $order->status == 'settlement') ? 'status-lunas' : 'status-error');
                            $statusLabel = ($order->status == 'pending') ? 'Belum Bayar' : (($order->status == 'success' || $order->status == 'settlement') ? 'Lunas' : 'Gagal');
                        @endphp
                        <span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td data-label="Aksi">
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            @if($order->status == 'pending')
                            <form action="{{ route('owner.pesanan.status', $order->id) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="success">
                                <button type="submit" class="btn-aksi btn-update" style="padding: 8px 12px;" title="Tandai Lunas"><i class="fas fa-check"></i></button>
                            </form>
                            @endif
                            <form action="{{ route('owner.pesanan.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-aksi btn-hapus" style="padding: 8px 12px;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center; color: #94a3b8; padding: 50px;">Belum ada riwayat transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection