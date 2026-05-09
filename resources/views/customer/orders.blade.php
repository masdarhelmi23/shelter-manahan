@extends('layouts.customer')

@section('title', 'Riwayat Pesanan')

@section('extra-css')
<style>
    /* =========================================
        PREMIUM DARK AESTHETIC (CLEAN GLASS)
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

    .container { width: 95%; max-width: 1300px; margin: 30px auto; padding-bottom: 100px; }

    /* HEADER PAGE */
    .page-header { margin-bottom: 30px; text-align: left; border-left: 5px solid #0284c7; padding-left: 20px; }
    .page-header h1 { color: #fff; font-size: clamp(24px, 5vw, 32px); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

    /* GLASS CARD STYLE */
    .orders-glass-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 30px;
        padding: 30px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .orders-table { width: 100%; border-collapse: collapse; }
    .orders-table th { 
        text-align: left; padding: 15px; color: #94a3b8; font-size: 11px; 
        text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 2px solid #f1f5f9; 
    }
    .orders-table td { padding: 20px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

    /* INFO STYLING */
    .order-id { font-size: 15px; font-weight: 900; color: #0f172a; margin-bottom: 4px; }
    .shop-name { font-weight: 800; color: #0284c7; font-size: 14px; text-transform: uppercase; }
    .price-main { font-weight: 900; color: #16a34a; font-size: 17px; display: block; }

    /* STATUS BADGES */
    .status-badge {
        padding: 6px 12px; border-radius: 10px; font-size: 10px; font-weight: 900; 
        text-transform: uppercase; display: inline-flex; align-items: center; gap: 6px;
    }
    .status-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .status-success { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }

    /* BUTTON ACTION */
    .btn-detail {
        background: #0f172a; color: #fff; padding: 12px 18px; border-radius: 12px; 
        border: none; cursor: pointer; font-weight: 800; font-size: 10px; transition: 0.3s;
        display: inline-flex; align-items: center; gap: 6px; text-transform: uppercase;
    }
    .btn-detail:hover { background: #0284c7; transform: translateY(-2px); }

    /* =========================================
        MOBILE RESPONSIVE REVISION
    ========================================= */
    @media (max-width: 992px) {
        .orders-table thead { display: none; }
        .orders-table, .orders-table tbody, .orders-table tr, .orders-table td { display: block; width: 100%; }
        
        .orders-table tr { 
            background: #fff; margin-bottom: 20px; border: 1px solid #e2e8f0; 
            border-radius: 20px; padding: 15px; position: relative;
        }
        
        .orders-table td { 
            border-bottom: none; padding: 10px 0; display: flex; 
            justify-content: space-between; align-items: center; 
            text-align: right;
        }

        .orders-table td::before {
            content: attr(data-label);
            font-weight: 800; color: #94a3b8; text-transform: uppercase;
            font-size: 10px; text-align: left; flex: 1;
            margin-right: 15px;
        }

        .mobile-data-wrapper {
            flex: 2;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .btn-detail { width: 100%; justify-content: center; margin-top: 10px; }
        .orders-glass-card { padding: 15px; border-radius: 25px; }
    }

    /* POPUP NOTA */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.95); backdrop-filter: blur(10px);
        display: none; justify-content: center; align-items: center;
        z-index: 999999; padding: 15px;
    }
    .modal-overlay.active { display: flex; }

    .nota-box {
        background: #fff; width: 100%; max-width: 450px;
        border-radius: 30px; overflow-y: auto; max-height: 90vh;
        box-shadow: 0 30px 70px rgba(0,0,0,0.5);
        animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    .nota-header { background: #0f172a; padding: 25px; color: #fff; text-align: center; position: relative; }
    .nota-body { padding: 25px; }
    .nota-footer { padding: 15px 25px 25px; border-top: 1px dashed #e2e8f0; }

    .nota-item { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #475569; }
    .total-row { display: flex; justify-content: space-between; margin-top: 15px; padding-top: 15px; border-top: 2px solid #f1f5f9; }

    .unpaid-bill { background: #fef2f2; color: #ef4444; padding: 15px; border-radius: 15px; text-align: center; margin-top: 15px; font-weight: 800; border: 1px solid #fee2e2; }
    .paid-bill { background: #f0fdf4; color: #16a34a; padding: 15px; border-radius: 15px; text-align: center; margin-top: 15px; font-weight: 800; border: 1px solid #dcfce7; }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Riwayat Pesanan</h1>
    </div>

    <div class="orders-glass-card">
        @if($groupedOrders && $groupedOrders->count() > 0)
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Invoice & Toko</th>
                        <th>Pelanggan</th>
                        <th style="text-align: center;">Status</th>
                        <th>Total Bayar</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedOrders as $order)
                        <tr>
                            <td data-label="Invoice">
                                <div class="mobile-data-wrapper">
                                    <div class="order-id">#{{ $order->order_id }}</div>
                                    <!-- MENAMPILKAN NAMA WARUNG -->
                                    <div class="shop-name"><i class="fa-solid fa-store"></i> {{ $order->shop->name ?? 'Warung Shelter' }}</div>
                                    <div style="font-size: 11px; color: #94a3b8; margin-top: 5px;">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                                </div>
                            </td>
                            <td data-label="Pelanggan">
                                <div class="mobile-data-wrapper">
                                    <div style="font-weight: 800; color: #0f172a; font-size: 15px;">{{ $order->customer_name ?? auth()->user()->name }}</div>
                                    <div style="font-size: 12px; color: #16a34a; font-weight: 700;">
                                        <i class="fa-brands fa-whatsapp"></i> {{ $order->customer_whatsapp ?? '-' }}
                                    </div>
                                </div>
                            </td>
                            <td data-label="Status">
                                <div class="mobile-data-wrapper" style="align-items: center;">
                                    @if($order->status == 'pending')
                                        <span class="status-badge status-pending">BELUM BAYAR</span>
                                    @else
                                        <span class="status-badge status-success">LUNAS</span>
                                    @endif
                                </div>
                            </td>
                            <td data-label="Total">
                                <div class="mobile-data-wrapper">
                                    <span class="price-main">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                                </div>
                            </td>
                            <td data-label="Aksi">
                                <button class="btn-detail" onclick="openNota('{{ $order->order_id }}', '{{ $order->status }}', '{{ $order->payment_method }}', '{{ $order->amount }}', '{{ $order->customer_name }}', '{{ $order->customer_whatsapp }}', {{ json_encode($order->details) }}, '{{ $order->shop->name ?? 'Warung Shelter' }}')">
                                    <i class="fa-solid fa-file-invoice"></i> Lihat Nota
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 50px 0; color: #94a3b8;">
                <i class="fa-solid fa-receipt fa-4x" style="margin-bottom: 20px; opacity: 0.2;"></i>
                <p style="font-weight: 700;">Belum ada riwayat pesanan.</p>
            </div>
        @endif
    </div>
</div>

<!-- POPUP MODAL NOTA -->
<div class="modal-overlay" id="modalNota" onclick="closeNota(event)">
    <div class="nota-box" onclick="event.stopPropagation()">
        <div class="nota-header">
            <!-- NAMA TOKO DI HEADER NOTA -->
            <div id="notaShopName" style="font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: #fcd34d; margin-bottom: 5px;">NAMA WARUNG</div>
            <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.7;">Struk Pembayaran</div>
            <h2 id="notaId" style="font-weight: 900; margin-top: 5px;">#ORDER-ID</h2>
            <i id="notaIcon" class="fa-solid fa-circle-check" style="position: absolute; bottom: -20px; left: 50%; transform: translateX(-50%); font-size: 40px; color: #fff; background: #16a34a; border-radius: 50%; border: 5px solid #fff;"></i>
        </div>
        
        <div class="nota-body">
            <div style="text-align: center; margin-bottom: 25px; margin-top: 10px;">
                <div id="notaCustName" style="font-weight: 800; font-size: 16px; color: #0f172a;">Nama Customer</div>
                <div id="notaCustWA" style="font-size: 12px; color: #64748b;">08123456789</div>
            </div>

            <div id="notaItems"></div>

            <div class="total-row">
                <span style="font-weight: 700; color: #0f172a;">Metode Bayar</span>
                <span id="notaMethod" style="font-weight: 800; color: #0284c7;">-</span>
            </div>
            <div class="total-row" style="border-top: none; padding-top: 5px;">
                <span style="font-weight: 800; color: #0f172a; font-size: 16px;">Total Akhir</span>
                <span id="notaTotal" style="font-weight: 900; color: #16a34a; font-size: 18px;">Rp 0</span>
            </div>
        </div>

        <div class="nota-footer">
            <div id="notaStatusBox"></div>
            <button onclick="document.getElementById('modalNota').classList.remove('active')" style="width: 100%; margin-top: 20px; padding: 15px; border-radius: 15px; border: none; background: #f1f5f9; color: #475569; font-weight: 800; cursor: pointer;">TUTUP NOTA</button>
        </div>
    </div>
</div>

<script>
    function openNota(id, status, method, amount, name, wa, details, shopName) {
        document.getElementById('notaId').innerText = '#' + id;
        document.getElementById('notaShopName').innerText = shopName; // Set Nama Warung
        document.getElementById('notaCustName').innerText = name || '{{ auth()->user()->name }}';
        document.getElementById('notaCustWA').innerText = wa || '-';
        document.getElementById('notaMethod').innerText = (method === 'midtrans') ? 'Transfer Virtual Account' : 'Tunai di Kasir';
        document.getElementById('notaTotal').innerText = 'Rp ' + parseInt(amount).toLocaleString('id-ID');

        let itemsHtml = '';
        details.forEach(item => {
            itemsHtml += `
                <div class="nota-item">
                    <span style="flex: 1; text-align: left;">${item.qty}x ${item.product ? item.product.nama_produk : 'Produk'}</span>
                    <span style="font-weight: 600;">Rp ${(item.qty * (item.product ? item.product.harga : 0)).toLocaleString('id-ID')}</span>
                </div>`;
        });
        document.getElementById('notaItems').innerHTML = itemsHtml;

        const statusBox = document.getElementById('notaStatusBox');
        const notaIcon = document.getElementById('notaIcon');
        if (status === 'pending') {
            statusBox.innerHTML = `<div class="unpaid-bill">SISA TAGIHAN: Rp ${parseInt(amount).toLocaleString('id-ID')}</div>`;
            notaIcon.className = "fa-solid fa-clock";
            notaIcon.style.background = "#f59e0b";
        } else {
            statusBox.innerHTML = `<div class="paid-bill">STATUS: TERBAYAR LUNAS</div>`;
            notaIcon.className = "fa-solid fa-circle-check";
            notaIcon.style.background = "#16a34a";
        }

        document.getElementById('modalNota').classList.add('active');
    }

    function closeNota(e) {
        if (e.target.id === 'modalNota') {
            document.getElementById('modalNota').classList.remove('active');
        }
    }
</script>
@endsection