@extends('layouts.app')

@section('content')
<!-- SweetAlert2 untuk Popup Notifikasi & Konfirmasi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Typography Dasar */
    .judul-halaman { font-size: 14px; color: #64748b; font-weight: 600; margin-bottom: 2px; }
    .subjudul-halaman { font-size: 16px; color: #fff; font-weight: 700; display: block; margin-bottom: 30px; }

    /* Kartu Ringkasan Total WD */
    .summary-wd-card {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: 25px;
        padding: 25px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        backdrop-filter: blur(10px);
        animation: fadeIn 0.8s ease-out;
    }
    .summary-wd-icon {
        width: 55px; height: 55px;
        background: #10b981;
        color: white;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
    }
    .summary-wd-info h4 { color: #94a3b8; font-size: 11px; text-transform: uppercase; margin: 0; letter-spacing: 1.5px; font-weight: 800; }
    .summary-wd-info h2 { color: #4ade80; font-size: 28px; font-weight: 900; margin: 5px 0 0 0; letter-spacing: -0.5px; }

    /* Container Mewah Tabel */
    .kartu-admin-mewah {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 35px;
        padding: 40px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        animation: fadeIn 1s ease-out;
    }

    /* Styling Tabel */
    .table-admin { width: 100%; border-collapse: collapse; color: #fff; }
    .table-admin th { 
        text-align: left; 
        padding: 15px 12px; 
        color: #fcd34d; 
        font-size: 11px; 
        font-weight: 800; 
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .table-admin td { 
        padding: 20px 12px; 
        font-size: 13px; 
        vertical-align: middle;
        border-bottom: 1px solid rgba(255,255,255,0.03);
    }

    /* Badge Status */
    .badge-status {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 9px;
        font-weight: 900;
        letter-spacing: 0.5px;
        display: inline-block;
        text-transform: uppercase;
    }
    .status-menunggu { background: rgba(252, 211, 77, 0.1); color: #fcd34d; border: 1px solid rgba(252, 211, 77, 0.2); }
    .status-berhasil { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
    .status-ditolak { background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); }

    /* Tombol Aksi */
    .btn-aksi {
        padding: 9px 18px;
        border-radius: 50px;
        font-size: 9px;
        font-weight: 900;
        color: #fff;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: 0.3s;
        text-transform: uppercase;
    }
    .btn-approve { background: #10b981; }
    .btn-approve:hover { background: #059669; transform: translateY(-2px); }
    .btn-reject { background: #ef4444; }
    .btn-reject:hover { background: #b91c1c; transform: translateY(-2px); }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* RESPONSIVE MOBILE OPTIMIZATION */
    @media (max-width: 992px) {
        .kartu-admin-mewah { padding: 20px; border-radius: 25px; }
        
        .table-admin thead { display: none; } /* Sembunyikan header tabel di mobile */
        .table-admin, .table-admin tbody, .table-admin tr, .table-admin td {
            display: block;
            width: 100%;
        }

        .table-admin tr {
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 20px;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .table-admin td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right;
            padding: 12px 5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .table-admin td:last-child { border-bottom: none; justify-content: center; padding-top: 20px; }

        /* Label untuk Mobile */
        .table-admin td::before {
            content: attr(data-label);
            font-weight: 800;
            color: #fcd34d;
            text-transform: uppercase;
            font-size: 10px;
            text-align: left;
        }

        .summary-wd-card { padding: 20px; gap: 15px; }
        .summary-wd-info h2 { font-size: 22px; }
    }
</style>

<div class="judul-halaman">Manajemen Keuangan</div>
<span class="subjudul-halaman">Verifikasi Penarikan Dana Tenant</span>

<!-- Total Dana yang Pernah di WD -->
<div class="summary-wd-card">
    <div class="summary-wd-icon">
        <i class="fas fa-hand-holding-dollar"></i>
    </div>
    <div class="summary-wd-info">
        <h4>Total Akumulasi Dana Dicairkan</h4>
        <h2>Rp {{ number_format($withdrawals->where('status', 'success')->sum('amount'), 0, ',', '.') }}</h2>
    </div>
</div>

<div class="kartu-admin-mewah">
    <table class="table-admin">
        <thead>
            <tr>
                <th>Waktu Pengajuan</th>
                <th>Nama Warung</th>
                <th>Saldo Terkini</th>
                <th>Nominal WD</th>
                <th>Info Rekening</th>
                <th>Status</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($withdrawals as $wd)
            <tr>
                <td data-label="Waktu">
                    <div style="text-align: inherit;">
                        <span style="color: #fff; font-weight: 600;">{{ $wd->created_at->format('d M Y') }}</span><br>
                        <span style="color: #94a3b8; font-size: 11px;">{{ $wd->created_at->format('H:i') }} WIB</span>
                    </div>
                </td>
                <td data-label="Warung" style="font-weight: 800; color: #fff;">{{ $wd->shop->name ?? 'Toko Tidak Ditemukan' }}</td>
                <td data-label="Saldo Toko" style="color: #cbd5e1;">Rp {{ number_format($wd->shop->balance ?? 0, 0, ',', '.') }}</td>
                <td data-label="Nominal WD" style="color: #4ade80; font-weight: 800;">Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                <td data-label="Rekening" style="color: #cbd5e1; font-size: 12px;">{{ $wd->bank_info }}</td>
                <td data-label="Status">
                    <span class="badge-status status-{{ $wd->status == 'pending' ? 'menunggu' : ($wd->status == 'success' ? 'berhasil' : 'ditolak') }}">
                        {{ $wd->status == 'pending' ? 'MENUNGGU' : strtoupper($wd->status) }}
                    </span>
                </td>
                <td data-label="Tindakan">
                    @if($wd->status == 'pending')
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button type="button" class="btn-aksi btn-approve" onclick="confirmApprove({{ $wd->id }})">
                            <i class="fas fa-check"></i> APPROVE
                        </button>
                        <form id="form-approve-{{ $wd->id }}" action="{{ route('admin.withdrawals.approve', $wd->id) }}" method="POST" style="display:none;">@csrf</form>

                        <button type="button" class="btn-aksi btn-reject" onclick="confirmReject({{ $wd->id }})">
                            <i class="fas fa-times"></i> REJECT
                        </button>
                        <form id="form-reject-{{ $wd->id }}" action="{{ route('admin.withdrawals.reject', $wd->id) }}" method="POST" style="display:none;">@csrf</form>
                    </div>
                    @else
                        <div style="text-align: center; color: #475569; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                            <i class="fas fa-lock"></i> Terkunci
                        </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 60px; color: #475569;">
                    <i class="fas fa-folder-open fa-3x" style="display: block; margin-bottom: 15px; opacity: 0.3;"></i>
                    Belum ada pengajuan penarikan dana.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Script SweetAlert Konfirmasi -->
<script>
    function confirmApprove(id) {
        Swal.fire({
            title: 'Konfirmasi Pencairan?',
            text: "Sistem akan memotong saldo toko secara permanen. Pastikan Anda sudah mentransfer dana ke rekening tersebut.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#334155',
            confirmButtonText: 'Ya, Approve & Cairkan!',
            cancelButtonText: 'Batal',
            background: '#0f172a',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-approve-' + id).submit();
            }
        });
    }

    function confirmReject(id) {
        Swal.fire({
            title: 'Tolak Pengajuan?',
            text: "Dana tidak akan dipotong dari saldo tenant. Berikan alasan penolakan secara manual jika diperlukan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#334155',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal',
            background: '#0f172a',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-reject-' + id).submit();
            }
        });
    }
</script>

<!-- Notifikasi Session -->
@if(session('success'))
<script>
    Swal.fire({ 
        icon: 'success', 
        title: 'Berhasil!', 
        text: "{{ session('success') }}", 
        background: '#0f172a', 
        color: '#fff', 
        confirmButtonColor: '#10b981' 
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({ 
        icon: 'error', 
        title: 'Gagal!', 
        text: "{{ session('error') }}", 
        background: '#0f172a', 
        color: '#fff', 
        confirmButtonColor: '#ef4444' 
    });
</script>
@endif

@endsection