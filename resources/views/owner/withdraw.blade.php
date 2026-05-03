@extends('layouts.app')

@section('content')
<!-- SweetAlert2 untuk Popup Mewah -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Tipografi Header */
    .premium-title { font-size: 36px; font-weight: 900; color: #ffffff; letter-spacing: -1px; margin-bottom: 5px; }
    .premium-subtitle { font-size: 13px; color: #fcd34d; letter-spacing: 3px; text-transform: uppercase; font-weight: 700; margin-bottom: 40px; display: block; }
    
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
    .balance-icon { width: 45px; height: 45px; background: #10b981; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; margin-bottom: 15px; }
    .label-saldo-kecil { color: #cbd5e1; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px; }
    .nilai-saldo-premium { font-size: 48px; font-weight: 900; color: #ffffff; margin-bottom: 10px; font-family: 'Plus Jakarta Sans', sans-serif; }
    .pending-status { font-size: 14px; color: #f87171; font-weight: 700; margin-bottom: 30px; display: block; }

    /* Form & Input */
    .label-input { color: #ffffff; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; display: block; letter-spacing: 0.5px; }
    .input-premium { 
        width: 100%; 
        background: rgba(0, 0, 0, 0.5); 
        border: 1px solid rgba(255, 255, 255, 0.2); 
        color: #ffffff; 
        padding: 18px 25px; 
        border-radius: 18px; 
        outline: none; 
        transition: 0.3s;
        font-weight: 600;
        box-sizing: border-box;
    }
    .input-premium::placeholder { color: #64748b; font-weight: 500; }
    .input-premium:focus { border-color: #10b981; background: rgba(0, 0, 0, 0.7); }

    .btn-submit-premium { 
        width: 100%; 
        padding: 20px; 
        background: #10b981; 
        border: none; 
        border-radius: 20px; 
        color: white; 
        font-weight: 800; 
        text-transform: uppercase; 
        letter-spacing: 1.5px; 
        cursor: pointer; 
        transition: 0.3s;
        margin-top: 10px;
    }
    .btn-submit-premium:hover { background: #059669; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); }

    /* Syarat & Ketentuan */
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
    .text-amount { font-weight: 800; color: #ffffff; font-size: 16px; }
    .text-bank { color: #94a3b8; font-weight: 500; }

    /* Badge Status */
    .status-badge { padding: 6px 16px; border-radius: 50px; font-size: 10px; font-weight: 900; text-transform: uppercase; display: inline-block; }
    .status-pending { background: rgba(252, 211, 77, 0.15); color: #fbbf24; border: 1px solid rgba(252, 211, 77, 0.3); }
    .status-success { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
    .status-rejected { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }

    /* =========================================
       RESPONSIVE MOBILE OPTIMIZATION
    ========================================= */
    @media (max-width: 768px) {
        .premium-title { font-size: 28px; text-align: center; }
        .premium-subtitle { font-size: 11px; text-align: center; margin-bottom: 25px; }
        
        .glass-container { grid-template-columns: 1fr; gap: 20px; }
        .kartu-luxury { padding: 25px; border-radius: 25px; }
        .nilai-saldo-premium { font-size: 32px; }

        .table-luxury-container { padding: 15px; }
        
        /* Hide regular table header on mobile */
        .table-premium thead { display: none; }
        
        /* Table rows become cards on mobile */
        .table-premium, .table-premium tbody, .table-premium tr, .table-premium td { 
            display: block; 
            width: 100%; 
        }
        
        .table-premium tr { 
            margin-bottom: 15px; 
            background: rgba(255,255,255,0.03); 
            border-radius: 20px; 
            padding: 15px;
            border: 1px solid rgba(255,255,255,0.05);
        }
        
        .table-premium td { 
            border: none; 
            padding: 8px 0; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            text-align: right;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .table-premium td:last-child { border-bottom: none; }
        
        /* Add labels using data-label (optional but cleaner with pseudo) */
        .table-premium td::before {
            content: attr(data-label);
            font-weight: 800;
            color: #94a3b8;
            font-size: 10px;
            text-transform: uppercase;
            text-align: left;
        }
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="premium-title">Penarikan Saldo</div>
<span class="premium-subtitle">Exclusive Financial Portal</span>

<div class="glass-container">
    <!-- Form Penarikan -->
    <div class="kartu-luxury">
        <div class="balance-icon"><i class="fas fa-wallet"></i></div>
        <span class="label-saldo-kecil">Saldo Tersedia</span>
        <div class="nilai-saldo-premium">Rp {{ number_format($shop->balance, 0, ',', '.') }}</div>
        
        @php $pendingAmount = $withdrawals->where('status', 'pending')->sum('amount'); @endphp
        @if($pendingAmount > 0)
            <span class="pending-status">
                <i class="fas fa-history"></i> -Rp {{ number_format($pendingAmount, 0, ',', '.') }} (Sedang Pengajuan)
            </span>
        @else
            <div style="margin-bottom: 30px;"></div>
        @endif

        <form action="{{ route('owner.withdraw') }}" method="POST">
            @csrf
            <div style="margin-bottom: 25px;">
                <label class="label-input">Nominal Penarikan</label>
                <input type="number" name="amount" class="input-premium" placeholder="Min. 10.000" min="10000" max="{{ $shop->balance }}" required>
            </div>
            <div style="margin-bottom: 35px;">
                <label class="label-input">Informasi Rekening</label>
                <textarea name="bank_info" class="input-premium" style="height: 110px; resize: none;" placeholder="Contoh: BCA - 0865654 - Masdar Helmi" required></textarea>
            </div>
            <button type="submit" class="btn-submit-premium">Konfirmasi Penarikan</button>
        </form>
    </div>

    <!-- Syarat & Ketentuan -->
    <div class="kartu-luxury" style="background: rgba(2, 132, 199, 0.08);">
        <h3 class="sk-title">Syarat & Ketentuan</h3>
        <div class="sk-item">
            <i class="fas fa-check-circle"></i>
            <span>Minimal penarikan dana ditetapkan sebesar <b>Rp 10.000</b> per transaksi.</span>
        </div>
        <div class="sk-item">
            <i class="fas fa-check-circle"></i>
            <span>Estimasi waktu pencairan dana adalah <b>1x24 Jam</b> hari kerja.</span>
        </div>
        <div class="sk-item">
            <i class="fas fa-check-circle"></i>
            <span>Pastikan data bank dan nomor rekening sudah sesuai untuk menghindari kegagalan sistem.</span>
        </div>
    </div>
</div>

<!-- Riwayat Aktivitas -->
<span class="section-divider">Riwayat Aktivitas</span>
<div class="table-luxury-container">
    <table class="table-premium">
        <thead>
            <tr>
                <th>Tanggal & Waktu</th>
                <th>Nominal</th>
                <th>Tujuan Rekening</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($withdrawals as $wd)
            <tr>
                <td data-label="Waktu" class="text-date">{{ $wd->created_at->format('d M Y, H:i') }}</td>
                <td data-label="Nominal" class="text-amount">Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                <td data-label="Tujuan" class="text-bank">{{ $wd->bank_info }}</td>
                <td data-label="Status">
                    <span class="status-badge status-{{ $wd->status }}">
                        {{ $wd->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 50px; color: #94a3b8; font-weight: 600;">Belum ada riwayat aktivitas penarikan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Scripts -->
@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", background: '#0f172a', color: '#fff', confirmButtonColor: '#10b981' });
</script>
@endif

@endsection