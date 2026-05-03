@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .judul-halaman { font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 5px; }
    .subjudul-halaman { font-size: 12px; color: #fcd34d; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; margin-bottom: 30px; display: block; }

    .kartu-mewah {
        background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px);
        border-radius: 25px; padding: 30px; border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .input-mewah {
        width: 100%; padding: 14px; border-radius: 12px; border: 1.5px solid rgba(255,255,255,0.1);
        background: rgba(15, 23, 42, 0.6); color: #fff; margin-bottom: 15px; outline: none; font-size: 14px;
    }
    .input-mewah:focus { border-color: #0284c7; }
    .label-mewah { color: #94a3b8; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; display: block; }

    .baris-produk { 
        background: rgba(255,255,255,0.03); padding: 20px; border-radius: 20px;
        margin-bottom: 15px; border: 1px solid rgba(255,255,255,0.05);
        display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 12px; align-items: end;
    }

    .btn-aksi { padding: 12px 20px; border-radius: 12px; border: none; font-weight: 800; cursor: pointer; color: #fff; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; justify-content: center; }

    @media (max-width: 768px) {
        .baris-produk { grid-template-columns: 1fr; gap: 10px; position: relative; padding-top: 45px; }
        .btn-hapus-item { position: absolute; top: 12px; right: 12px; }
        .grid-mobile { grid-template-columns: 1fr !important; }
    }
</style>

<div class="judul-halaman">Buat Pesanan Baru</div>
<span class="subjudul-halaman">Input transaksi manual - {{ $shop->name }}</span>

<div class="kartu-mewah">
    <form action="{{ route('owner.pesanan.store') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;" class="grid-mobile">
            <div>
                <label class="label-mewah">Nama Pemesan</label>
                <input type="text" name="customer_name" class="input-mewah" placeholder="Nama Customer" required>
            </div>
            <div>
                <label class="label-mewah">WhatsApp</label>
                <input type="number" name="customer_whatsapp" class="input-mewah" placeholder="628..." required>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin: 20px 0 10px;">
            <h4 style="color: #fff;">Daftar Menu</h4>
            <button type="button" class="btn-aksi" style="background:#0284c7; font-size: 11px;" onclick="tambahBarisProduk()">
                <i class="fas fa-plus"></i> Tambah Item
            </button>
        </div>

        <div id="container-produk">
            <div class="baris-produk" id="row-0">
                <div>
                    <label class="label-mewah">Produk</label>
                    <select name="items[0][product_id]" class="input-mewah" required onchange="updateHargaBaris(0)">
                        <option value="" disabled selected>Pilih Menu</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" data-price="{{ $p->harga }}">{{ $p->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label-mewah">Qty</label>
                    <input type="number" name="items[0][qty]" class="input-mewah input-qty" value="1" min="1" required oninput="updateHargaBaris(0)">
                </div>
                <div>
                    <label class="label-mewah">Subtotal</label>
                    <input type="text" class="input-mewah input-subtotal" value="0" readonly style="background: rgba(0,0,0,0.5); border: none;">
                </div>
                <div class="btn-hapus-item">
                    <button type="button" class="btn-aksi" style="background:#dc2626; padding: 12px;" onclick="hapusBaris(0)"><i class="fas fa-trash-can"></i></button>
                </div>
            </div>
        </div>

        <div style="background: rgba(15, 23, 42, 0.6); padding: 20px; border-radius: 20px; margin-top: 20px; border: 1px solid rgba(255,255,255,0.05);">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;" class="grid-mobile">
                <div>
                    <label class="label-mewah">Metode Bayar</label>
                    <select name="payment_method" class="input-mewah">
                        <option value="cashier">Bayar di Kasir</option>
                        <option value="midtrans">Transfer Bank</option>
                    </select>
                </div>
                <div>
                    <label class="label-mewah">Status</label>
                    <select name="status" class="input-mewah">
                        <option value="pending">Belum Bayar</option>
                        <option value="success">Lunas</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                <span style="color: #94a3b8; font-weight: 700; font-size: 12px;">TOTAL PEMBAYARAN</span>
                <span style="color: #4ade80; font-weight: 900; font-size: 26px;" id="total-akhir">Rp 0</span>
            </div>
            <input type="hidden" name="amount" id="amount_hidden" value="0">
        </div>

        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <a href="{{ route('owner.pesanan') }}" class="btn-aksi" style="background: #475569; flex: 1;">Batal</a>
            <button type="submit" class="btn-aksi" style="background: #16a34a; flex: 2;">Simpan Transaksi</button>
        </div>
    </form>
</div>

<script>
    let barisKe = 1;
    function tambahBarisProduk() {
        const container = document.getElementById('container-produk');
        const barisBaru = document.createElement('div');
        barisBaru.className = 'baris-produk';
        barisBaru.id = `row-${barisKe}`;
        barisBaru.innerHTML = `
            <div>
                <select name="items[${barisKe}][product_id]" class="input-mewah" required onchange="updateHargaBaris(${barisKe})">
                    <option value="" disabled selected>Pilih Menu</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->harga }}">{{ $p->nama_produk }}</option>
                    @endforeach
                </select>
            </div>
            <div><input type="number" name="items[${barisKe}][qty]" class="input-mewah input-qty" value="1" min="1" oninput="updateHargaBaris(${barisKe})"></div>
            <div><input type="text" class="input-mewah input-subtotal" value="0" readonly style="background: rgba(0,0,0,0.5); border: none;"></div>
            <div class="btn-hapus-item"><button type="button" class="btn-aksi" style="background:#dc2626; padding: 12px;" onclick="hapusBaris(${barisKe})"><i class="fas fa-trash-can"></i></button></div>
        `;
        container.appendChild(barisBaru);
        barisKe++;
    }

    function hapusBaris(id) {
        if(document.querySelectorAll('.baris-produk').length > 1) {
            document.getElementById(`row-${id}`).remove();
            hitungTotalAkhir();
        }
    }

    function updateHargaBaris(id) {
        const row = document.getElementById(`row-${id}`);
        const select = row.querySelector('select');
        const qty = row.querySelector('.input-qty').value;
        const subtotalInput = row.querySelector('.input-subtotal');
        const price = select.options[select.selectedIndex].getAttribute('data-price') || 0;
        subtotalInput.value = (price * qty).toLocaleString('id-ID');
        hitungTotalAkhir();
    }

    function hitungTotalAkhir() {
        let total = 0;
        document.querySelectorAll('.baris-produk').forEach(row => {
            const select = row.querySelector('select');
            const qty = row.querySelector('.input-qty').value || 0;
            const price = (select.selectedIndex >= 0) ? (select.options[select.selectedIndex].getAttribute('data-price') || 0) : 0;
            total += (price * parseInt(qty));
        });
        document.getElementById('total-akhir').innerText = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('amount_hidden').value = total;
    }
</script>
@endsection