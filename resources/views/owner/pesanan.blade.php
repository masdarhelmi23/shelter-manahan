@extends('layouts.app')

@section('content')
<!-- Library FontAwesome & SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    
    .btn-aksi { padding: 10px 15px; border-radius: 12px; border: none; font-size: 11px; font-weight: 800; cursor: pointer; transition: 0.3s; color: #fff; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: 1px solid transparent; }
    .btn-aksi:hover { transform: translateY(-3px); }
    
    .btn-tambah { background: #0284c7; box-shadow: 0 10px 20px rgba(2, 132, 199, 0.3); font-size: 14px; padding: 15px 25px; }
    .btn-update { background: #16a34a; }
    .btn-hapus { background: #dc2626; }

    /* CUSTOM SWEETALERT UI */
    .swal2-popup.swal-custom {
        background: rgba(15, 23, 42, 0.9) !important;
        backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 25px !important;
        color: #fff !important;
    }
    .swal2-title { color: #fff !important; }
    .swal2-html-container { color: #cbd5e1 !important; }
    .swal2-confirm { border-radius: 12px !important; padding: 12px 25px !important; font-weight: 700 !important; }
    .swal2-cancel { border-radius: 12px !important; padding: 12px 25px !important; font-weight: 700 !important; }

    /* ================= RESPONSIVE MOBILE REVISION ================= */
    @media (max-width: 768px) {
        .header-aksi { flex-direction: column; align-items: center; text-align: center; }
        .btn-tambah { width: 100%; justify-content: center; }

        .tabel-pesanan thead { display: none; }
        .tabel-pesanan, .tabel-pesanan tbody, .tabel-pesanan tr, .tabel-pesanan td { display: block; width: 100%; }
        .tabel-pesanan tr { margin-bottom: 20px; border-radius: 20px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.1); }
        .tabel-pesanan td { 
            display: flex; justify-content: space-between; align-items: center; 
            padding: 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); text-align: right;
        }
        .tabel-pesanan td::before { content: attr(data-label); font-weight: 800; color: #fcd34d; font-size: 10px; text-align: left; text-transform: uppercase; }
        .tabel-pesanan td:last-child { border-bottom: none; }
    }
</style>

<div class="header-aksi">
    <div>
        <div class="judul-halaman">Manajemen Pesanan</div>
        <span class="subjudul-halaman">Daftar Transaksi - {{ $shop->name }}</span>
    </div>
    <a href="{{ route('owner.pesanan.create') }}" class="btn-aksi btn-tambah">
        <i class="fas fa-plus"></i> Buat Pesanan Baru
    </a>
</div>

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
                            <form action="{{ route('owner.pesanan.status', $order->id) }}" method="POST" class="form-lunas">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="success">
                                <button type="button" class="btn-aksi btn-update btn-confirm-lunas" style="padding: 8px 12px;" title="Tandai Lunas">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            
                            <form action="{{ route('owner.pesanan.destroy', $order->id) }}" method="POST" class="form-hapus">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-aksi btn-hapus btn-confirm-hapus" style="padding: 8px 12px;">
                                    <i class="fas fa-trash"></i>
                                </button>
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

<script>
    // 1. Popup Sukses (Jika ada session success)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            background: 'rgba(15, 23, 42, 0.9)',
            color: '#fff',
            customClass: { popup: 'swal-custom' },
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    @endif

    // 2. Konfirmasi Hapus
    document.querySelectorAll('.btn-confirm-hapus').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.form-hapus');
            Swal.fire({
                title: 'Hapus Transaksi?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: 'rgba(15, 23, 42, 0.9)',
                color: '#fff',
                customClass: { popup: 'swal-custom' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // 3. Konfirmasi Tandai Lunas
    document.querySelectorAll('.btn-confirm-lunas').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.form-lunas');
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Tandai transaksi ini sebagai Lunas?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Lunas!',
                cancelButtonText: 'Batal',
                background: 'rgba(15, 23, 42, 0.9)',
                color: '#fff',
                customClass: { popup: 'swal-custom' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection