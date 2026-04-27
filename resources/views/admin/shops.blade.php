@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .management-container { padding: 20px 0; min-height: 100vh; font-family: 'Inter', sans-serif; }
    
    .page-header { 
        border-left: 8px solid #fcd34d; 
        padding-left: 20px; 
        margin-bottom: 40px; 
    }
    .page-header h1 { font-size: 36px; font-weight: 800; color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.3); }
    .page-header p { color: #fcd34d; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 13px; }

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
    }
    .custom-table tbody tr:hover { 
        background: rgba(255, 255, 255, 0.1); 
        transform: scale(1.01); 
    }
    
    .custom-table td { padding: 20px; vertical-align: middle; color: #ffffff; border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); }

    /* Status Badges Neon */
    .status-badge { padding: 8px 16px; border-radius: 12px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
    .status-active { background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3); }
    .status-pending { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3); }

    /* Dropdown Status Style */
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
        text-transform: uppercase;
        appearance: none;
    }
    .select-status option { background: #1e293b; color: white; }

    .btn-save-status {
        background: #0284c7;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }
    .btn-save-status:hover { background: #0ea5e9; transform: scale(1.1); box-shadow: 0 5px 15px rgba(2, 132, 199, 0.4); }

    .store-icon {
        width: 45px; 
        height: 45px; 
        background: rgba(252, 211, 77, 0.2); 
        color: #fcd34d; 
        border-radius: 12px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 20px;
        border: 1px solid rgba(252, 211, 77, 0.3);
    }
</style>

<div class="management-container">
    <div class="container">
        <div class="page-header">
            <h1>Manajemen Toko</h1>
            <p>Kontrol Otoritas & Status Operasional Shelter Manahan</p>
        </div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.2); color: #4ade80; padding: 20px; border-radius: 20px; margin-bottom: 30px; border: 1px solid rgba(74, 222, 128, 0.3);">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="luxury-card">
            <div style="overflow-x: auto;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Identitas Toko</th>
                            <th>Pemilik Sistem</th>
                            <th>Status Saat Ini</th>
                            <th>Tgl Registrasi</th>
                            <th style="text-align: center;">Otorisasi Perubahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shops as $index => $shop)
                        <tr>
                            <td style="color: #94a3b8; font-weight: 800;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div class="store-icon">
                                        <i class="fa-solid fa-store"></i>
                                    </div>
                                    <span style="font-weight: 800; font-size: 16px;">{{ $shop->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 14px; font-weight: 700;">{{ $shop->user->name ?? 'N/A' }}</div>
                                <div style="font-size: 11px; color: #94a3b8;">{{ $shop->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="status-badge {{ $shop->status == 'active' ? 'status-active' : 'status-pending' }}">
                                    {{ $shop->status == 'active' ? 'AKTIF' : 'NON-AKTIF' }}
                                </span>
                            </td>
                            <td style="color: #cbd5e1; font-size: 13px; font-weight: 600;">{{ $shop->created_at->format('d M Y') }}</td>
                            <td style="text-align: center;">
                                <form action="{{ route('admin.shops.approve', $shop->id) }}" method="POST" style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                                    @csrf
                                    <select name="status" class="select-status">
                                        <option value="active" {{ $shop->status == 'active' ? 'selected' : '' }}>SET AKTIF</option>
                                        <option value="pending" {{ $shop->status == 'pending' ? 'selected' : '' }}>SET NON-AKTIF</option>
                                    </select>
                                    <button type="submit" class="btn-save-status" title="Update Status">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 80px; color: #94a3b8;">
                                <i class="fa-solid fa-database" style="font-size: 40px; display: block; margin-bottom: 20px; opacity: 0.3;"></i>
                                <span style="font-style: italic;">Tidak ada data toko yang ditemukan.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection