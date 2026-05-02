@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* TYPOGRAPHY & LAYOUT */
    .judul-halaman { font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 5px; }
    .subjudul-halaman { font-size: 13px; color: #fcd34d; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; margin-bottom: 30px; display: block; }
    
    .header-aksi { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px; }

    .kartu-mewah {
        background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px);
        border-radius: 25px; padding: 30px; border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* TABEL STYLE */
    .tabel-pesanan { width: 100%; border-collapse: separate; border-spacing: 0 15px; }
    .tabel-pesanan th { color: #fcd34d; text-align: left; padding: 10px 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
    .tabel-pesanan td { background: rgba(255, 255, 255, 0.05); color: #fff; padding: 20px; vertical-align: middle; }
    .tabel-pesanan tr td:first-child { border-radius: 15px 0 0 15px; }
    .tabel-pesanan tr td:last-child { border-radius: 0 15px 15px 0; }

    /* RINCIAN ITEM */
    .rincian-list { list-style: none; padding: 0; margin: 0; }
    .rincian-item { 
        font-size: 13px; 
        padding: 5px 0; 
        border-bottom: 1px solid rgba(255,255,255,0.05);
        display: flex;
        justify-content: space-between;
        gap: 15px;
    }
    .rincian-item:last-child { border-bottom: none; }

    /* STATUS & BUTTONS */
    .badge-status { padding: 6px 12px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; display: inline-block; }
    .status-pending { background: rgba(252, 211, 77, 0.2); color: #fcd34d; }
    .status-lunas { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
    .status-error { background: rgba(220, 38, 38, 0.2); color: #f87171; }
    
    .btn-aksi { padding: 10px 15px; border-radius: 12px; border: none; font-size: 11px; font-weight: 800; cursor: pointer; transition: 0.3s; color: #fff; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
    .btn-tambah { background: #0284c7; box-shadow: 0 10px 20px rgba(2, 132, 199, 0.3); font-size: 14px; padding: 15px 25px; }
    .btn-update { background: #16a34a; }
    .btn-hapus { background: #dc2626; }
    .btn-small { padding: 5px 10px; border-radius: 8px; font-size: 10px; }

    /* MODAL STYLE */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(10px);
        display: none; justify-content: center; align-items: center; z-index: 2000;
    }
    .modal-konten {
        background: #1e293b; border: 1px solid rgba(255,255,255,0.1);
        padding: 30px; border-radius: 30px; width: 95%; max-width: 700px;
        max-height: 90vh; overflow-y: auto; position: relative;
    }
    
    .input-mewah {
        width: 100%; padding: 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.1);
        background: rgba(0,0,0,0.2); color: #fff; margin-bottom: 10px; outline: none; font-size: 14px;
    }
    .input-mewah option { background: #1e293b; color: #fff; }
    .label-mewah { color: #fcd34d; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; display: block; margin-top: 10px; }

    /* GRID UNTUK FORM */
    .grid-form { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

    /* BARIS DINAMIS */
    .baris-produk { 
        display: grid; 
        grid-template-columns: 2fr 1fr 1fr auto; 
        gap: 10px; 
        align-items: end;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
</style>

<div class="header-aksi">
    <div>
        <div class="judul-halaman">Manajemen Pesanan</div>
        <span class="subjudul-halaman">Daftar Transaksi - {{ $shop->name }}</span>
    </div>
    <button class="btn-aksi btn-tambah" onclick="toggleModal('modalTambah')">
        <i class="fas fa-plus"></i> Buat Pesanan Baru
    </button>
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
                    <th>ID / Waktu</th>
                    <th>Info Pemesan</th>
                    <th>Rincian Produk</th>
                    <th>Total & Metode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <div style="font-weight: 800; color: #fcd34d; margin-bottom: 5px;">#{{ $order->order_id }}</div>
                        <div style="font-size: 12px; color: #94a3b8;">{{ $order->created_at->format('d/m/Y H:i') }} WIB</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #fff;">{{ $order->customer_name ?? 'Umum' }}</div>
                        <div style="font-size: 12px; color: #fcd34d;"><i class="fab fa-whatsapp"></i> {{ $order->customer_whatsapp ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="rincian-list">
                            @if($order->details && $order->details->count() > 0)
                                @foreach($order->details as $item)
                                    <div class="rincian-item">
                                        <span>{{ $item->qty }}x {{ $item->product->nama_produk ?? 'Produk Dihapus' }}</span>
                                        <span style="color: #94a3b8;">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            @else
                                <span style="font-size: 11px; color: #f87171;">Data produk tidak tersedia</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 900; color: #4ade80; font-size: 16px;">
                            Rp{{ number_format($order->amount, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 10px; color: #94a3b8; text-transform: uppercase; margin-top: 5px;">
                            <i class="fas fa-credit-card"></i> {{ $order->payment_method == 'midtrans' ? 'Transfer / QRIS' : 'Bayar di Kasir' }}
                        </div>
                    </td>
                    <td>
                        @php
                            $statusClass = 'status-pending';
                            $statusLabel = 'Belum Bayar';
                            if(in_array($order->status, ['settlement', 'success'])) {
                                $statusClass = 'status-lunas';
                                $statusLabel = 'Lunas';
                            } elseif(in_array($order->status, ['expire', 'cancel', 'failed'])) {
                                $statusClass = 'status-error';
                                $statusLabel = 'Gagal';
                            }
                        @endphp
                        <span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            @if(!in_array($order->status, ['settlement', 'success']))
                            <form action="{{ route('owner.pesanan.status', $order->id) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="success">
                                <button type="submit" class="btn-aksi btn-update btn-small" title="Tandai Lunas"><i class="fas fa-check"></i></button>
                            </form>
                            @endif
                            <form action="{{ route('owner.pesanan.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-aksi btn-hapus btn-small" title="Hapus"><i class="fas fa-trash"></i></button>
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

{{-- MODAL TAMBAH PESANAN --}}
<div class="modal-overlay" id="modalTambah">
    <div class="modal-konten">
        <h3 style="color:#fff; margin-bottom:20px; display: flex; justify-content: space-between; align-items: center;">
            Buat Transaksi Baru
            <button type="button" class="btn-aksi btn-update btn-small" onclick="tambahBarisProduk()"><i class="fas fa-plus"></i> Tambah Item</button>
        </h3>
        
        <form action="{{ route('owner.pesanan.store') }}" method="POST" id="formTransaksi">
            @csrf
            
            <div class="grid-form">
                <div>
                    <label class="label-mewah">Nama Pemesan</label>
                    <input type="text" name="customer_name" class="input-mewah" placeholder="Contoh: Budi Santoso" required>
                </div>
                <div>
                    <label class="label-mewah">Nomor WhatsApp</label>
                    <input type="text" name="customer_whatsapp" class="input-mewah" placeholder="Contoh: 08123xxx" required>
                </div>
            </div>

            <div id="container-produk" style="margin-top: 15px;">
                <!-- Baris Produk Pertama -->
                <div class="baris-produk" id="row-0">
                    <div class="form-group">
                        <label class="label-mewah">Pilih Produk</label>
                        <select name="items[0][product_id]" class="input-mewah select-produk" required onchange="updateHargaBaris(0)">
                            <option value="" disabled selected>Pilih Produk</option>
                            @foreach($shop->products as $p)
                                <option value="{{ $p->id }}" data-price="{{ $p->harga }}">{{ $p->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label-mewah">Jumlah</label>
                        <input type="number" name="items[0][qty]" class="input-mewah input-qty" value="1" min="1" required oninput="updateHargaBaris(0)">
                    </div>
                    <div class="form-group">
                        <label class="label-mewah">Subtotal</label>
                        <input type="text" class="input-mewah input-subtotal" value="0" readonly>
                    </div>
                    <div style="padding-bottom: 10px;">
                        <button type="button" class="btn-aksi btn-hapus btn-small" onclick="hapusBaris(0)"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>

            <div style="background: rgba(0,0,0,0.3); padding: 20px; border-radius: 15px; margin-top: 10px;">
                <div class="grid-form">
                    <div>
                        <label class="label-mewah">Metode Pembayaran</label>
                        <select name="payment_method" class="input-mewah">
                            <option value="cashier">Bayar di Kasir (Tunai)</option>
                            <option value="midtrans">Transfer / QRIS (Midtrans)</option>
                        </select>
                    </div>
                    <div>
                        <label class="label-mewah">Status Awal</label>
                        <select name="status" class="input-mewah">
                            <option value="pending">Belum Bayar (Pending)</option>
                            <option value="success">Lunas (Success)</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 15px; align-items: center; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                    <span style="color: #94a3b8; font-weight: 700;">TOTAL KESELURUHAN</span>
                    <span style="color: #4ade80; font-weight: 900; font-size: 24px;" id="total-akhir">Rp 0</span>
                </div>
                <input type="hidden" name="amount" id="amount_hidden" value="0">
            </div>

            <div style="display:flex; gap:10px; margin-top:20px;">
                <button type="button" class="btn-aksi btn-hapus" style="flex:1;" onclick="toggleModal('modalTambah')">Batal</button>
                <button type="submit" class="btn-aksi btn-tambah" style="flex:2;">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>

<script>
    let barisKe = 1;

    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.style.display = (modal.style.display === "flex") ? "none" : "flex";
    }

    function tambahBarisProduk() {
        const container = document.getElementById('container-produk');
        const barisBaru = document.createElement('div');
        barisBaru.className = 'baris-produk';
        barisBaru.id = `row-${barisKe}`;
        
        barisBaru.innerHTML = `
            <div class="form-group">
                <select name="items[${barisKe}][product_id]" class="input-mewah select-produk" required onchange="updateHargaBaris(${barisKe})">
                    <option value="" disabled selected>Pilih Produk</option>
                    @foreach($shop->products as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->harga }}">{{ $p->nama_produk }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="number" name="items[${barisKe}][qty]" class="input-mewah input-qty" value="1" min="1" required oninput="updateHargaBaris(${barisKe})">
            </div>
            <div class="form-group">
                <input type="text" class="input-mewah input-subtotal" value="0" readonly>
            </div>
            <div style="padding-bottom: 10px;">
                <button type="button" class="btn-aksi btn-hapus btn-small" onclick="hapusBaris(${barisKe})"><i class="fas fa-times"></i></button>
            </div>
        `;
        container.appendChild(barisBaru);
        barisKe++;
    }

    function hapusBaris(id) {
        const row = document.getElementById(`row-${id}`);
        if(document.querySelectorAll('.baris-produk').length > 1) {
            row.remove();
            hitungTotalAkhir();
        } else {
            alert("Minimal harus ada satu produk dalam pesanan!");
        }
    }

    function updateHargaBaris(id) {
        const row = document.getElementById(`row-${id}`);
        const select = row.querySelector('.select-produk');
        const qty = row.querySelector('.input-qty').value;
        const subtotalInput = row.querySelector('.input-subtotal');
        
        const price = select.options[select.selectedIndex].getAttribute('data-price') || 0;
        const totalLine = price * qty;
        subtotalInput.value = totalLine.toLocaleString('id-ID');
        hitungTotalAkhir();
    }

    function hitungTotalAkhir() {
        let total = 0;
        document.querySelectorAll('.baris-produk').forEach(row => {
            const select = row.querySelector('.select-produk');
            const qty = row.querySelector('.input-qty').value || 0;
            const price = select.options[select.selectedIndex].getAttribute('data-price') || 0;
            total += (price * parseInt(qty));
        });
        
        document.getElementById('total-akhir').innerText = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('amount_hidden').value = total;
    }

    // Menutup modal saat klik di luar area konten
    window.onclick = function(event) {
        if (event.target.className === 'modal-overlay') {
            event.target.style.display = "none";
        }
    }
</script>
@endsection