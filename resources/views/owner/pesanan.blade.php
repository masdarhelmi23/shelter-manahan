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
    .tabel-pesanan td { background: rgba(255, 255, 255, 0.05); color: #fff; padding: 20px; vertical-align: middle; transition: 0.3s; }
    .tabel-pesanan tr td:first-child { border-radius: 15px 0 0 15px; }
    .tabel-pesanan tr td:last-child { border-radius: 0 15px 15px 0; }
    
    /* Hover Effect untuk Double Click Indicator */
    .row-pesanan:hover td { background: rgba(255, 255, 255, 0.1); cursor: pointer; }

    /* STATUS & BUTTONS */
    .badge-status { padding: 6px 12px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; display: inline-block; }
    .status-pending { background: rgba(252, 211, 77, 0.2); color: #fcd34d; }
    .status-lunas { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
    .status-error { background: rgba(220, 38, 38, 0.2); color: #f87171; }
    
    .btn-aksi { padding: 8px 15px; border-radius: 10px; border: none; font-size: 11px; font-weight: 800; cursor: pointer; transition: 0.3s; color: #fff; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: 1px solid transparent; letter-spacing: 0.5px; }
    .btn-aksi:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
    
    .btn-tambah { background: #0284c7; font-size: 14px; padding: 15px 25px; box-shadow: 0 10px 20px rgba(2, 132, 199, 0.3); }
    .btn-update { background: #16a34a; }
    .btn-diterima { background: #0ea5e9; } 
    .btn-hapus { background: #dc2626; padding: 8px 12px; }

    /* CUSTOM SWEETALERT UI */
    .swal2-popup.swal-custom {
        background: rgba(15, 23, 42, 0.95) !important;
        backdrop-filter: blur(25px) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 25px !important;
        color: #fff !important;
    }
    .swal2-title { color: #fcd34d !important; font-weight: 900 !important; }
    .swal2-html-container { color: #cbd5e1 !important; margin: 1em 0 0.3em !important;}
    .swal2-confirm, .swal2-cancel { border-radius: 12px !important; padding: 12px 25px !important; font-weight: 800 !important; }
    
    /* Mini Table untuk Rincian */
    .rincian-list { list-style: none; padding: 0; margin: 0; font-size: 12px; color: #cbd5e1; }
    .rincian-list li { margin-bottom: 4px; padding-bottom: 4px; border-bottom: 1px dashed rgba(255,255,255,0.1); }
    .rincian-list li:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }

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
        
        .rincian-list { text-align: right; }
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
    <div style="font-size: 12px; color: #94a3b8; margin-bottom: 15px;">
        <i class="fas fa-info-circle"></i> <i>Tips: Klik 2x (Double Click) pada baris pesanan untuk melihat nota penuh.</i>
    </div>
    <div class="table-responsive">
        <table class="tabel-pesanan">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Pemesan</th>
                    <th>Rincian</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php
                    $statusOrder = strtolower($order->status);
                    
                    if ($statusOrder == 'pending') {
                        $statusClass = 'status-pending';
                        $statusLabel = 'Belum Bayar';
                    } elseif (in_array($statusOrder, ['success', 'settlement', 'lunas'])) {
                        $statusClass = 'status-lunas';
                        $statusLabel = 'Lunas / Dikemas';
                    } else {
                        $statusClass = 'status-error';
                        $statusLabel = 'Gagal';
                    }
                @endphp
                <tr class="row-pesanan" id="row-order-{{ $order->id }}" ondblclick="lihatDetail({{ $order->id }})" title="Klik dua kali untuk melihat detail lengkap">
                    <td data-label="Invoice">
                        <div style="font-weight: 800; color: #fcd34d;">#{{ $order->order_id }}</div>
                        <div style="font-size: 11px; color: #94a3b8;">{{ $order->created_at->format('d/m/y H:i') }}</div>
                    </td>
                    <td data-label="Pemesan">
                        <div style="font-weight: 700;">{{ $order->customer_name ?? 'Umum' }}</div>
                        <div style="font-size: 11px; color: #4ade80;">{{ $order->customer_whatsapp ?? '-' }}</div>
                    </td>
                    <td data-label="Rincian">
                        <ul class="rincian-list">
                            @if(isset($order->details) && $order->details->count() > 0)
                                @foreach($order->details->take(2) as $detail)
                                    <li>{{ $detail->qty }}x {{ $detail->product->nama_produk ?? 'Produk Dihapus' }}</li>
                                @endforeach
                                @if($order->details->count() > 2)
                                    <li style="color: #0284c7; font-weight: 700; border:none;">+ {{ $order->details->count() - 2 }} item lainnya...</li>
                                @endif
                            @else
                                <li>- Tidak ada rincian -</li>
                            @endif
                        </ul>
                    </td>
                    <td data-label="Total">
                        <div style="font-weight: 900; color: #fff; font-size: 15px;">Rp{{ number_format($order->amount, 0, ',', '.') }}</div>
                    </td>
                    <td data-label="Pembayaran">
                        <div style="font-size: 11px; color: #cbd5e1; font-weight: 700; text-transform: uppercase;">
                            {{ $order->payment_method == 'midtrans' ? 'Transfer Bank' : 'Bayar di Kasir' }}
                        </div>
                    </td>
                    <td data-label="Status">
                        <span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td data-label="Aksi">
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            
                            <!-- JIKA BELUM BAYAR: TOMBOL BAYAR DI KASIR -->
                            @if($statusOrder == 'pending')
                            <form action="{{ route('owner.pesanan.status', $order->id) }}" method="POST" class="form-lunas">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="success">
                                <button type="button" class="btn-aksi btn-update btn-confirm-lunas">
                                    BAYAR DI KASIR
                                </button>
                            </form>
                            
                            <!-- JIKA SUDAH LUNAS: TOMBOL SUDAH DITERIMA -->
                            @elseif(in_array($statusOrder, ['success', 'settlement', 'lunas']))
                            <button type="button" class="btn-aksi btn-diterima" onclick="tandaiSelesai(event, {{ $order->id }})">
                                SUDAH DITERIMA
                            </button>
                            @endif
                            
                            <!-- TOMBOL HAPUS -->
                            <form action="{{ route('owner.pesanan.destroy', $order->id) }}" method="POST" class="form-hapus">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-aksi btn-hapus btn-confirm-hapus" title="Hapus Pesanan">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                
                <!-- TEMPLATE HIDDEN UNTUK POPUP SWEETALERT -->
                <template id="detail-pesanan-{{ $order->id }}">
                    <div style="text-align: left; font-size: 14px; padding-top: 10px;">
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; margin-bottom: 15px;">
                            <div>
                                <div style="color: #94a3b8; font-size: 12px; text-transform: uppercase; font-weight: 700;">Pelanggan</div>
                                <div style="font-weight: 800; font-size: 18px; color: #fff;">{{ $order->customer_name ?? 'Umum' }}</div>
                                <div style="color: #4ade80;"><i class="fab fa-whatsapp"></i> {{ $order->customer_whatsapp ?? '-' }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="color: #94a3b8; font-size: 12px; text-transform: uppercase; font-weight: 700;">Tanggal & Waktu</div>
                                <div style="color: #fff; font-weight: 600;">{{ $order->created_at->format('d F Y') }}</div>
                                <div style="color: #cbd5e1;">{{ $order->created_at->format('H:i') }} WIB</div>
                            </div>
                        </div>

                        <div style="color: #fcd34d; font-size: 13px; text-transform: uppercase; font-weight: 800; margin-bottom: 10px;">Rincian Produk</div>
                        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                            <thead>
                                <tr style="border-bottom: 1px dashed rgba(255,255,255,0.2); color: #94a3b8;">
                                    <th style="text-align: left; padding: 8px 0;">Item</th>
                                    <th style="text-align: center; padding: 8px 0;">Qty</th>
                                    <th style="text-align: right; padding: 8px 0;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($order->details))
                                    @foreach($order->details as $detail)
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                        <td style="padding: 12px 0; color: #fff;">{{ $detail->product->nama_produk ?? 'Produk Terhapus' }}<br><small style="color: #94a3b8;">Rp {{ number_format($detail->price, 0, ',', '.') }}</small></td>
                                        <td style="padding: 12px 0; text-align: center; color: #fff; font-weight: 700;">{{ $detail->qty }}</td>
                                        <td style="padding: 12px 0; text-align: right; color: #fff; font-weight: 700;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        <div style="margin-top: 20px; background: rgba(0,0,0,0.3); padding: 15px; border-radius: 15px; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800;">Metode Pembayaran</div>
                                <div style="color: #cbd5e1; font-weight: 600;">{{ $order->payment_method == 'midtrans' ? 'Transfer Bank' : 'Bayar di Kasir' }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800;">Total Pembayaran</div>
                                <div style="font-size: 20px; font-weight: 900; color: #4ade80;">Rp {{ number_format($order->amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- END TEMPLATE HIDDEN -->
                
                @empty
                <tr><td colspan="7" style="text-align: center; color: #94a3b8; padding: 50px;">Belum ada riwayat transaksi sama sekali.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // ==========================================
    // LOGIKA PENYEMBUNYIAN PESANAN OTOMATIS
    // ==========================================
    document.addEventListener("DOMContentLoaded", function() {
        let hiddenOrders = JSON.parse(localStorage.getItem('pesananSelesai')) || [];
        hiddenOrders.forEach(function(orderId) {
            let row = document.getElementById('row-order-' + orderId);
            if(row) {
                row.style.display = 'none';
            }
        });
    });

    // Fungsi ketika tombol SUDAH DITERIMA diklik
    function tandaiSelesai(event, orderId) {
        event.stopPropagation(); 
        
        Swal.fire({
            title: 'Pesanan Selesai?',
            text: "Apakah pesanan ini sudah selesai dan sudah diambil oleh pelanggan?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#0ea5e9',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Ya, Sudah!',
            cancelButtonText: 'Batal',
            background: 'rgba(15, 23, 42, 0.95)',
            color: '#fff',
            customClass: { popup: 'swal-custom' }
        }).then((result) => {
            if (result.isConfirmed) {
                let hiddenOrders = JSON.parse(localStorage.getItem('pesananSelesai')) || [];
                if (!hiddenOrders.includes(orderId)) {
                    hiddenOrders.push(orderId);
                    localStorage.setItem('pesananSelesai', JSON.stringify(hiddenOrders));
                }
                
                document.getElementById('row-order-' + orderId).style.display = 'none';
                
                Swal.fire({
                    icon: 'success',
                    title: 'Selesai!',
                    text: 'Pesanan telah selesai dan disembunyikan.',
                    background: 'rgba(15, 23, 42, 0.95)',
                    color: '#fff',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }

    // FUNGSI UNTUK MENAMPILKAN POPUP DETAIL (DOUBLE CLICK)
    function lihatDetail(orderId) {
        const detailHTML = document.getElementById('detail-pesanan-' + orderId).innerHTML;
        
        Swal.fire({
            title: 'Detail Transaksi',
            html: detailHTML,
            width: '600px',
            showCloseButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Tutup Nota',
            confirmButtonColor: '#0284c7',
            background: 'rgba(15, 23, 42, 0.95)',
            color: '#fff',
            customClass: { popup: 'swal-custom' }
        });
    }

    // Popup Sukses Bawaan Controller
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            background: 'rgba(15, 23, 42, 0.95)',
            color: '#fff',
            customClass: { popup: 'swal-custom' },
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    @endif

    // Konfirmasi Hapus Data Permanen
    document.querySelectorAll('.btn-confirm-hapus').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); 
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
                background: 'rgba(15, 23, 42, 0.95)',
                color: '#fff',
                customClass: { popup: 'swal-custom' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Konfirmasi Tandai Lunas (Bayar di Kasir)
    document.querySelectorAll('.btn-confirm-lunas').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); 
            const form = this.closest('.form-lunas');
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Tandai pesanan ini sudah dibayar lunas di kasir?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Lunas!',
                cancelButtonText: 'Batal',
                background: 'rgba(15, 23, 42, 0.95)',
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