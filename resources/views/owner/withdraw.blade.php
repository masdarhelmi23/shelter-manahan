@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Tipografi Header */
    .premium-title { font-size: 36px; font-weight: 900; color: #ffffff; letter-spacing: -1px; margin-bottom: 5px; }
    .premium-subtitle { font-size: 13px; color: #38bdf8; letter-spacing: 3px; text-transform: uppercase; font-weight: 700; display: block; }
    
    /* Wrapper Header & Filter */
    .header-wrapper { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; flex-wrap: wrap; gap: 20px; }
    
    /* Layout Utama */
    .glass-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; animation: fadeIn 0.8s ease-out; margin-bottom: 40px; }
    
    .kartu-luxury {
        background: rgba(255, 255, 255, 0.05); 
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 35px;
        padding: 40px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.4);
    }

    /* Informasi Saldo */
    .balance-icon { width: 45px; height: 45px; background: #38bdf8; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; margin-bottom: 15px; }
    .label-saldo-kecil { color: #cbd5e1; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px; }
    .nilai-saldo-premium { font-size: 48px; font-weight: 900; color: #ffffff; margin-bottom: 10px; font-family: 'Plus Jakarta Sans', sans-serif; }
    .income-status { font-size: 13px; color: #94a3b8; font-weight: 600; display: block; background: rgba(0,0,0,0.2); padding: 10px 15px; border-radius: 12px; display: inline-block;}
    .income-status b { color: #34d399; }

    /* Input Filter Premium */
    .filter-box { background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1); padding: 12px 25px; border-radius: 20px; display: flex; align-items: center; gap: 15px; }
    .filter-box label { color: #cbd5e1; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
    .filter-input { background: transparent; border: none; color: #38bdf8; font-size: 16px; font-weight: 800; outline: none; cursor: pointer; color-scheme: dark; font-family: inherit; }

    /* Informasi Laporan */
    .sk-title { color: #ffffff; font-size: 20px; font-weight: 800; margin-bottom: 25px; }
    .sk-item { color: #e2e8f0; font-size: 14px; line-height: 1.6; margin-bottom: 18px; display: flex; align-items: flex-start; gap: 12px; }
    .sk-item i { color: #38bdf8; font-size: 16px; margin-top: 3px; }

    /* Riwayat Aktivitas */
    .section-divider { font-size: 24px; font-weight: 900; color: #ffffff; margin: 60px 0 30px 0; display: block; }
    .table-luxury-container { background: rgba(0, 0, 0, 0.3); border-radius: 30px; border: 1px solid rgba(255,255,255,0.1); padding: 10px 35px; overflow-x: hidden; }
    .table-premium { width: 100%; border-collapse: collapse; }
    .table-premium th { text-align: left; padding: 25px 15px; color: #94a3b8; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1.5px; }
    .table-premium td { padding: 25px 15px; font-size: 14px; color: #ffffff; border-bottom: 1px solid rgba(255,255,255,0.05); }
    
    .text-date { color: #cbd5e1; font-weight: 500; }
    .text-amount { font-weight: 800; color: #10b981; font-size: 16px; }
    .text-customer { color: #94a3b8; font-weight: 500; }

    /* Badge Status */
    .status-badge { padding: 6px 16px; border-radius: 50px; font-size: 10px; font-weight: 900; text-transform: uppercase; display: inline-block; }
    .status-pending { background: rgba(252, 211, 77, 0.15); color: #fbbf24; border: 1px solid rgba(252, 211, 77, 0.3); }
    .status-success { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
    .status-settlement { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }

    /* Responsive Mobile */
    @media (max-width: 768px) {
        .header-wrapper { flex-direction: column; align-items: flex-start; }
        .filter-box { width: 100%; justify-content: space-between; }
        .premium-title { font-size: 28px; }
        .glass-container { grid-template-columns: 1fr; gap: 20px; }
        .kartu-luxury { padding: 25px; border-radius: 25px; }
        .nilai-saldo-premium { font-size: 32px; }
        .table-luxury-container { padding: 15px; }
        .table-premium thead { display: none; }
        .table-premium, .table-premium tbody, .table-premium tr, .table-premium td { display: block; width: 100%; }
        .table-premium tr { margin-bottom: 15px; background: rgba(255,255,255,0.03); border-radius: 20px; padding: 15px; border: 1px solid rgba(255,255,255,0.05); }
        .table-premium td { border: none; padding: 8px 0; display: flex; justify-content: space-between; align-items: center; text-align: right; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .table-premium td:last-child { border-bottom: none; }
        .table-premium td::before { content: attr(data-label); font-weight: 800; color: #94a3b8; font-size: 10px; text-transform: uppercase; text-align: left; }
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="header-wrapper">
    <div>
        <div class="premium-title">Laporan Keuangan</div>
        <span class="premium-subtitle">Ringkasan Pemasukan & Pendapatan</span>
    </div>

    <!-- Form Filter Bulan -->
    <form action="{{ route('owner.withdraw.index') }}" method="GET" class="filter-box">
        <label for="bulan">Periode Bulan</label>
        <input type="month" name="bulan" id="bulan" value="{{ $filterBulan }}" class="filter-input" onchange="this.form.submit()">
    </form>
</div>

<div class="glass-container">
    <!-- Total Pendapatan Bulan Ini -->
    <div class="kartu-luxury">
        <div class="balance-icon"><i class="fas fa-chart-line"></i></div>
        <span class="label-saldo-kecil">Pendapatan Bulan {{ \Carbon\Carbon::parse($filterBulan)->translatedFormat('F Y') }}</span>
        
        <!-- Nominal Berdasarkan Filter Bulan -->
        <div class="nilai-saldo-premium">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
        
        <span class="income-status">
            <i class="fas fa-wallet"></i> Total Pendapatan Keseluruhan: <b>Rp {{ number_format($totalPendapatanAsli, 0, ',', '.') }}</b>
        </span>
    </div>

    <!-- Informasi / Catatan -->
    <div class="kartu-luxury" style="background: rgba(2, 132, 199, 0.08);">
        <h3 class="sk-title">Informasi Pemasukan</h3>
        <div class="sk-item">
            <i class="fas fa-check-circle"></i>
            <span>Laporan ini hanya menampilkan pesanan dengan status <b>Selesai (Success/Settlement)</b>.</span>
        </div>
        <div class="sk-item">
            <i class="fas fa-check-circle"></i>
            <span>Ubah periode bulan pada pojok kanan atas untuk melihat riwayat pemasukan di bulan lainnya.</span>
        </div>
        <div class="sk-item">
            <i class="fas fa-check-circle"></i>
            <span>Total Pendapatan Keseluruhan adalah kalkulasi uang yang Anda dapatkan sejak awal membuka toko.</span>
        </div>
    </div>
</div>

<!-- Riwayat Pemasukan -->
<span class="section-divider">Riwayat Pemasukan ({{ \Carbon\Carbon::parse($filterBulan)->translatedFormat('F Y') }})</span>
<div class="table-luxury-container">
    <table class="table-premium">
        <thead>
            <tr>
                <th>Tanggal & Waktu</th>
                <th>ID Pesanan</th>
                <th>Nama Pelanggan</th>
                <th>Nominal Masuk</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td data-label="Waktu" class="text-date">{{ $order->created_at->format('d M Y, H:i') }}</td>
                <td data-label="ID Pesanan" style="font-weight: 700; color: #cbd5e1;">{{ $order->order_id }}</td>
                <td data-label="Pelanggan" class="text-customer">{{ $order->customer_name ?? 'Pelanggan' }}</td>
                <td data-label="Nominal" class="text-amount">+ Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                <td data-label="Status">
                    <span class="status-badge status-{{ strtolower($order->status) }}">
                        {{ $order->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 50px; color: #94a3b8; font-weight: 600;">
                    Belum ada riwayat pendapatan pada bulan {{ \Carbon\Carbon::parse($filterBulan)->translatedFormat('F Y') }}.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection