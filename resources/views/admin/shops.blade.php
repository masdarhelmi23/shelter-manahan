@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .management-container { padding: 20px 0; min-height: 100vh; font-family: 'Inter', sans-serif; }
    
    .page-header { 
        border-left: 8px solid #fcd34d; 
        padding-left: 20px; 
        margin-bottom: 30px; 
    }
    .page-header h1 { font-size: 36px; font-weight: 800; color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.3); }
    .page-header p { color: #fcd34d; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 13px; }

    /* Layout Penyesuaian untuk Single Widget */
    .dashboard-grid-top {
        display: inline-flex;
        min-width: 300px;
        gap: 20px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 25px 35px 25px 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        backdrop-filter: blur(10px);
        height: 100%;
        box-sizing: border-box;
        width: 100%;
    }
    .summary-icon {
        width: 60px; height: 60px;
        background: #0284c7;
        color: white;
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        box-shadow: 0 10px 20px rgba(2, 132, 199, 0.3);
    }
    .summary-info h4 { color: #94a3b8; font-size: 12px; text-transform: uppercase; margin: 0; letter-spacing: 1px; }
    .summary-info h2 { color: #ffffff; font-size: 28px; font-weight: 900; margin: 5px 0 0 0; }

    /* Luxury Glass Card */
    .luxury-card { 
        background: rgba(255, 255, 255, 0.1); 
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 30px; 
        padding: 40px; 
        border: 1px solid rgba(255, 255, 255, 0.2); 
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    }
    
    /* Table Styling */
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0 15px; }
    .custom-table thead th { 
        color: #fcd34d; 
        text-transform: uppercase; 
        font-size: 11px; 
        letter-spacing: 2px; 
        padding: 15px; 
        font-weight: 800;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .custom-table tbody tr { 
        background: rgba(255, 255, 255, 0.05); 
        transition: all 0.3s ease; 
        cursor: pointer;
    }
    .custom-table tbody tr:hover { 
        background: rgba(255, 255, 255, 0.12); 
        transform: scale(1.005); 
    }
    
    .custom-table td { padding: 15px 20px; vertical-align: middle; color: #ffffff; border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); }

    .table-store-logo {
        width: 50px; height: 50px;
        border-radius: 12px;
        object-fit: cover;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.05);
    }

    .status-badge { padding: 8px 16px; border-radius: 12px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; white-space: nowrap; }
    .status-active { background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3); }
    .status-pending { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3); }

    .select-status {
        background: rgba(0, 0, 0, 0.3);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 10px 15px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 800;
        outline: none;
        cursor: pointer;
        width: 100%;
        max-width: 150px;
    }

    .btn-save-status, .btn-view-detail {
        width: 40px; height: 40px;
        border-radius: 12px; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        transition: 0.3s; border: none; color: white; flex-shrink: 0;
    }

    .btn-save-status { background: #0284c7; }
    .btn-save-status:hover { background: #0ea5e9; transform: translateY(-3px); }

    .btn-view-detail { background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); }
    .btn-view-detail:hover { background: #fcd34d; color: #000; transform: translateY(-3px); }

    .tooltip-text { font-size: 10px; color: #94a3b8; margin-top: 10px; display: block; text-align: center; }

    /* ================= RESPONSIVE MOBILE REVISION ================= */
    @media (max-width: 768px) {
        .page-header h1 { font-size: 26px; }
        .page-header p { font-size: 11px; }
        .dashboard-grid-top { display: flex; width: 100%; }
        .summary-card { padding: 15px; width: 100%; }
        .luxury-card { padding: 15px; border-radius: 20px; }

        .custom-table thead { display: none; }
        .custom-table, .custom-table tbody, .custom-table tr, .custom-table td { display: block; width: 100%; }
        .custom-table tr { margin-bottom: 20px; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.1); padding: 15px; background: rgba(255, 255, 255, 0.03); }
        .custom-table td { display: flex; justify-content: space-between; align-items: center; padding: 12px 5px; text-align: right; border: none !important; font-size: 13px; }
        .custom-table td:not(:last-child) { border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; }
        .custom-table td::before { content: attr(data-label); font-weight: 800; color: #fcd34d; text-transform: uppercase; font-size: 10px; text-align: left; margin-right: 10px; }
        .custom-table td[data-label="Identitas Toko"] { flex-direction: column; align-items: flex-end; gap: 10px; padding-top: 5px; }
        .custom-table td[data-label="Identitas Toko"]::before { width: 100%; margin-bottom: 5px; }
        .custom-table td[data-label="Aksi & Otoritas"] { justify-content: center; padding-top: 20px; }
        .select-status { max-width: 100%; }
    }
</style>

<div class="management-container">
    <div class="container">
        <div class="page-header">
            <h1>Manajemen Toko</h1>
            <p>Kontrol Otoritas & Status Operasional Shelter Manahan</p>
        </div>

        <!-- Dashboard Grid (Statistik Toko Tunggal) -->
        <div class="dashboard-grid-top">
            <div class="summary-card">
                <div class="summary-icon">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div class="summary-info">
                    <h4>Total Tenant Terdaftar</h4>
                    <h2>{{ $shops->count() }} Unit Toko</h2>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.2); color: #4ade80; padding: 15px; border-radius: 15px; margin-bottom: 30px; border: 1px solid rgba(74, 222, 128, 0.3); font-size: 14px;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="luxury-card">
            <div class="table-responsive-wrapper">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Identitas Toko</th>
                            <th>Pemilik Sistem</th>
                            <th>Status Saat Ini</th>
                            <th style="text-align: center;">Aksi & Otoritas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shops as $index => $shop)
                        <tr ondblclick="window.location='{{ route('admin.shops.show', $shop->id) }}'" title="Klik 2x untuk detail lengkap">
                            <td data-label="#">
                                <span style="color: #94a3b8; font-weight: 800;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td data-label="Identitas Toko">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    @if($shop->logo)
                                        <img src="{{ asset('storage/' . $shop->logo) }}" class="table-store-logo">
                                    @else
                                        <div class="table-store-logo" style="display: flex; align-items: center; justify-content: center; background: rgba(252, 211, 77, 0.1); color: #fcd34d;">
                                            <i class="fa-solid fa-store"></i>
                                        </div>
                                    @endif
                                    <div style="text-align: left;">
                                        <div style="font-weight: 800; font-size: 16px;">{{ $shop->name }}</div>
                                        <div style="font-size: 11px; color: #fcd34d;">ID: #SHP-{{ $shop->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Pemilik Sistem">
                                <div style="text-align: right;">
                                    <div style="font-size: 14px; font-weight: 700;">{{ $shop->user->name ?? 'N/A' }}</div>
                                    <div style="font-size: 11px; color: #94a3b8;">{{ $shop->user->email ?? '-' }}</div>
                                </div>
                            </td>
                            <td data-label="Status Saat Ini">
                                <span class="status-badge {{ $shop->status == 'active' ? 'status-active' : 'status-pending' }}">
                                    {{ $shop->status == 'active' ? 'AKTIF' : 'NON-AKTIF' }}
                                </span>
                            </td>
                            <td data-label="Aksi & Otoritas">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <a href="{{ route('admin.shops.show', $shop->id) }}" class="btn-view-detail" title="Lihat Profil & Riwayat">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <form action="{{ route('admin.shops.approve', $shop->id) }}" method="POST" style="display: flex; gap: 8px; margin: 0;">
                                        @csrf
                                        <select name="status" class="select-status">
                                            <option value="active" {{ $shop->status == 'active' ? 'selected' : '' }}>AKTIF</option>
                                            <option value="pending" {{ $shop->status == 'pending' ? 'selected' : '' }}>OFF</option>
                                        </select>
                                        <button type="submit" class="btn-save-status" title="Simpan Perubahan">
                                            <i class="fa-solid fa-shield-halved"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 60px; color: #94a3b8;">
                                <i class="fa-solid fa-database" style="font-size: 40px; display: block; margin-bottom: 20px; opacity: 0.3;"></i>
                                <span style="font-style: italic;">Tidak ada data toko yang ditemukan.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <span class="tooltip-text">* Tips: Klik dua kali pada kartu toko untuk melihat profil lengkap.</span>
            </div>
        </div>
    </div>
</div>
@endsection