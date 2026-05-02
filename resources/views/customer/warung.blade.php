@extends('layouts.customer')

@section('title', 'Menu ' . $shop->name)

@section('extra-css')
<style>
    /* =========================================
        MODERN EXECUTIVE DARK (STAY AUTHENTIC)
    ========================================= */
    body {
        background: 
            radial-gradient(circle at top right, rgba(2, 132, 199, 0.15), transparent),
            linear-gradient(to bottom, rgba(0, 0, 0, 0.92), rgba(15, 23, 42, 0.96)),
            url('https://www.transparenttextures.com/patterns/dark-matter.png'),
            url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2070&auto=format&fit=crop');
        background-size: cover; background-position: center; background-attachment: fixed;
    }

    /* SHOP HEADER */
    .shop-header { position: relative; padding: 60px 20px 40px; text-align: center; display: flex; flex-direction: column; align-items: center; }
    .shop-logo { width: 140px; height: 140px; border-radius: 35px; margin: 0 auto 25px; overflow: hidden; background: #fff; border: 4px solid rgba(255,255,255,0.2); box-shadow: 0 25px 50px rgba(0,0,0,0.5); }
    .shop-logo img { width: 100%; height: 100%; object-fit: cover; }
    .shop-name { font-size: 42px; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: -1px; text-shadow: 0 10px 30px rgba(0,0,0,0.8); margin-bottom: 10px; }
    
    .shop-hours { 
        display: flex; align-items: center; gap: 12px; margin-bottom: 25px; 
        color: #cbd5e1; font-size: 14px; font-weight: 600;
        background: rgba(255,255,255,0.05); padding: 8px 20px; border-radius: 50px;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; letter-spacing: 0.5px; }
    .badge-open { background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); }
    .badge-closed { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }

    .social-actions { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; }
    .btn-social { padding: 12px 25px; border-radius: 12px; font-size: 11px; font-weight: 800; display: flex; align-items: center; gap: 10px; background: rgba(255, 255, 255, 0.1); color: #fff; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.15); transition: 0.3s; text-transform: uppercase; text-decoration: none; }
    .btn-social:hover { background: var(--primary); transform: translateY(-3px); }

    /* MODERN SPLIT LAYOUT */
    .container { width: 92%; max-width: 1400px; margin: 0 auto; padding-bottom: 100px; }
    .main-grid { display: grid; grid-template-columns: 1fr 400px; gap: 35px; align-items: start; }

    /* MENU GRID */
    .grid-menu { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
    .card-menu { background: #fff; border-radius: 28px; overflow: hidden; border: 1px solid rgba(255,255,255,0.5); box-shadow: 0 10px 30px rgba(0,0,0,0.2); transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .card-menu:hover { transform: translateY(-12px); box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4); }
    .img-wrapper { width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background: #f8fafc; }
    .img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
    .card-body { padding: 22px; text-align: center; }
    .card-title { font-size: 19px; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
    .card-price { font-size: 21px; font-weight: 800; color: var(--primary); margin-bottom: 18px; }

    /* QTY SELECTOR */
    .qty-box { display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-radius: 15px; padding: 5px; border: 1px solid #e2e8f0; }
    .btn-qty { width: 38px; height: 38px; border: none; background: #fff; color: #0f172a; border-radius: 10px; cursor: pointer; font-weight: 800; transition: 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .btn-qty:hover { background: var(--primary); color: #fff; }
    .input-qty { width: 45px; border: none; background: transparent; text-align: center; font-weight: 800; font-size: 16px; }

    /* SIDEBAR RINCIAN (EXECUTIVE STYLE) */
    .sidebar-rincian { 
        position: sticky; top: 100px; background: rgba(255, 255, 255, 0.05); 
        backdrop-filter: blur(25px); border-radius: 35px; padding: 35px; 
        border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 40px 80px rgba(0,0,0,0.5); 
    }
    .sidebar-title { color: #fff; font-size: 22px; font-weight: 800; margin-bottom: 25px; display: flex; align-items: center; gap: 12px; letter-spacing: -0.5px; }
    .sidebar-title i { color: var(--primary); }

    /* FORM INPUT STYLE */
    .label-mewah { color: #fcd34d; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; display: block; }
    .input-mewah { width: 100%; padding: 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2); color: #fff; margin-bottom: 15px; outline: none; }

    .rincian-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; padding-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .rincian-info b { display: block; color: #fff; font-size: 15px; }
    .rincian-info span { color: #94a3b8; font-size: 12px; font-weight: 600; }
    .rincian-sub { color: #fff; font-weight: 800; font-size: 17px; }

    .total-section { background: rgba(2, 132, 199, 0.1); border-radius: 20px; padding: 25px; margin-top: 25px; border: 1px solid rgba(2, 132, 199, 0.2); }
    .total-label { color: #cbd5e1; font-size: 13px; font-weight: 700; text-transform: uppercase; margin-bottom: 5px; }
    .total-amount { color: #fff; font-size: 32px; font-weight: 900; }

    .btn-cart {
        width: 100%; margin-top: 25px; padding: 20px; border-radius: 18px; border: none;
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #fff; font-weight: 800; font-size: 14px; cursor: pointer;
        transition: 0.3s; text-transform: uppercase; letter-spacing: 1px;
        display: flex; align-items: center; justify-content: center; gap: 12px;
    }
    .btn-cart:hover:not(:disabled) { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(2, 132, 199, 0.4); }
    .btn-cart:disabled { opacity: 0.3; cursor: not-allowed; filter: grayscale(1); }

    /* CLOSED STATE OVERLAY */
    .closed-banner {
        background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3);
        padding: 15px; border-radius: 20px; color: #f87171; font-weight: 800;
        text-align: center; margin-bottom: 30px; backdrop-filter: blur(10px);
    }

    @media (max-width: 1150px) { .main-grid { grid-template-columns: 1fr; } .sidebar-rincian { position: static; margin-bottom: 40px; order: -1; } }
</style>
@endsection

@section('content')
    <header class="shop-header">
        <div class="shop-logo">
            @if($shop->logo)
                <img src="{{ asset('storage/' . $shop->logo) }}" alt="Logo">
            @else
                <div style="height:100%; display:flex; align-items:center; justify-content:center; background:#f8fafc;">
                    <i class="fas fa-store fa-3x" style="color:#cbd5e1;"></i>
                </div>
            @endif
        </div>
        <h1 class="shop-name">{{ $shop->name }}</h1>

        <!-- LOGIKA BUKA TUTUP (OPERASIONAL + MANUAL) -->
        @php
            $now = now();
            $open = \Carbon\Carbon::createFromTimeString($shop->open_time ?? '00:00');
            $close = \Carbon\Carbon::createFromTimeString($shop->close_time ?? '23:59');
            $isAutoOpen = $now->between($open, $close);
            $isOpen = $shop->is_active && $isAutoOpen;
        @endphp

        @if($shop->open_time && $shop->close_time)
            <div class="shop-hours">
                <i class="fa-solid fa-clock"></i>
                <span>{{ \Carbon\Carbon::parse($shop->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shop->close_time)->format('H:i') }}</span>
                <span class="status-badge {{ $isOpen ? 'badge-open' : 'badge-closed' }}">
                    {{ $isOpen ? 'BUKA' : 'TUTUP' }}
                </span>
            </div>
        @endif

        <div class="social-actions">
            @if($shop->whatsapp) 
                <a href="https://wa.me/{{ $shop->whatsapp }}" class="btn-social" target="_blank"><i class="fa-brands fa-whatsapp fa-lg"></i> WHATSAPP</a> 
            @endif
            @if($shop->instagram) 
                <a href="https://instagram.com/{{ $shop->instagram }}" class="btn-social" target="_blank"><i class="fa-brands fa-instagram fa-lg"></i> INSTAGRAM</a> 
            @endif
        </div>
    </header>

    <div class="container">
        <!-- BANNER PERINGATAN JIKA TUTUP -->
        @if(!$isOpen)
            <div class="closed-banner animate__animated animate__headShake">
                <i class="fa-solid fa-circle-exclamation"></i> MAAF, SAAT INI WARUNG SEDANG TUTUP. ANDA TIDAK DAPAT MELAKUKAN PEMESANAN.
            </div>
        @endif

        <div class="main-grid">
            <div class="menu-side">
                <!-- DIM MENU JIKA TUTUP -->
                <div class="grid-menu" style="{{ !$isOpen ? 'opacity: 0.5; pointer-events: none;' : '' }}">
                    @forelse($products as $product)
                        <div class="card-menu">
                            <div class="img-wrapper">
                                <img src="{{ $product->foto ? asset('storage/' . $product->foto) : asset('images/default-food.jpg') }}" alt="{{ $product->nama_produk }}">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">{{ $product->nama_produk }}</h3>
                                <div class="card-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                                
                                @if(Auth::check())
                                    <div class="qty-box">
                                        <button type="button" class="btn-qty" onclick="updateStruk({{ $product->id }}, '{{ $product->nama_produk }}', {{ $product->harga }}, -1)">-</button>
                                        <input type="number" class="input-qty" value="0" readonly id="qty-input-{{ $product->id }}">
                                        <button type="button" class="btn-qty" onclick="updateStruk({{ $product->id }}, '{{ $product->nama_produk }}', {{ $product->harga }}, 1)">+</button>
                                    </div>
                                @else
                                    <a href="{{ route('customer.login') }}" class="btn-cart" style="text-decoration:none;">LOGIN UNTUK PESAN</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p style="color: #fff; opacity: 0.5; text-align: center; grid-column: 1/-1;">Menu belum tersedia.</p>
                    @endforelse
                </div>
            </div>

            <aside class="sidebar-side">
                <div class="sidebar-rincian" style="{{ !$isOpen ? 'opacity: 0.7; pointer-events: none;' : '' }}">
                    <div class="sidebar-title">
                        <i class="fa-solid fa-receipt"></i> RINCIAN PESANAN
                    </div>

                    <!-- IDENTITAS CUSTOMER -->
                    <div style="margin-bottom: 20px;">
                        <label class="label-mewah">Nama Lengkap</label>
                        <input type="text" id="cust_name" class="input-mewah" placeholder="Nama Pesanan">
                        
                        <label class="label-mewah">Nomor WhatsApp</label>
                        <input type="number" id="cust_wa" class="input-mewah" placeholder="Contoh: 081234xxx">
                    </div>

                    <div id="struk-list">
                        <div style="text-align: center; padding: 20px 0; opacity: 0.2; color: #fff;">
                            <i class="fa-solid fa-basket-shopping fa-3x mb-3"></i>
                            <p>Belum ada menu dipilih</p>
                        </div>
                    </div>

                    <!-- METODE PEMBAYARAN -->
                    <div style="margin-top: 20px; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 15px; border: 1px solid rgba(255,255,255,0.1);">
                        <label class="label-mewah">Metode Pembayaran</label>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <label style="color: #fff; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                                <input type="radio" name="payment_method" value="cashier" checked onchange="renderStruk()"> 
                                Bayar di Kasir (Rp 0)
                            </label>
                            <label style="color: #fff; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                                <input type="radio" name="payment_method" value="midtrans" onchange="renderStruk()"> 
                                Transfer / Midtrans (+Rp 2.500)
                            </label>
                        </div>
                    </div>

                    <div class="total-section">
                        <div style="display: flex; justify-content: space-between; color: #94a3b8; font-size: 12px; margin-bottom: 5px;">
                            <span>Biaya Admin:</span>
                            <span id="txt-admin-fee">Rp 0</span>
                        </div>
                        <div class="total-label">Total Akhir</div>
                        <div class="total-amount" id="struk-total">Rp 0</div>
                    </div>

                    <button type="button" id="btn-gas-cart" class="btn-cart" disabled onclick="gasAddToCart()">
                        <i class="fa-solid fa-paper-plane"></i> {{ $isOpen ? 'KONFIRMASI PESANAN' : 'WARUNG TUTUP' }}
                    </button>
                </div>
            </aside>
        </div>
    </div>

    <script>
        let keranjangSementara = {};
        const ADMIN_FEE_MIDTRANS = 2500;

        function updateStruk(id, name, price, delta) {
            // Cek status buka lewat variabel Blade yang dipassing ke string
            if ("{{ $isOpen }}" == "" || "{{ $isOpen }}" == "0") return;

            if (!keranjangSementara[id]) {
                keranjangSementara[id] = { name: name, price: price, qty: 0 };
            }

            keranjangSementara[id].qty += delta;
            if (keranjangSementara[id].qty < 0) keranjangSementara[id].qty = 0;

            document.getElementById('qty-input-' + id).value = keranjangSementara[id].qty;
            renderStruk();
        }

        function renderStruk() {
            const container = document.getElementById('struk-list');
            const totalTxt = document.getElementById('struk-total');
            const adminFeeTxt = document.getElementById('txt-admin-fee');
            const btnCart = document.getElementById('btn-gas-cart');
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            let html = '';
            let subtotal = 0;
            let adaItem = false;

            for (let id in keranjangSementara) {
                let item = keranjangSementara[id];
                if (item.qty > 0) {
                    adaItem = true;
                    let sub = item.price * item.qty;
                    subtotal += sub;
                    html += `
                        <div class="rincian-item animate__animated animate__fadeIn">
                            <div class="rincian-info">
                                <b>${item.name}</b>
                                <span>${item.qty} Porsi x Rp ${item.price.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="rincian-sub">Rp ${sub.toLocaleString('id-ID')}</div>
                        </div>
                    `;
                }
            }

            let adminFee = (paymentMethod === 'midtrans') ? ADMIN_FEE_MIDTRANS : 0;
            let grandTotal = subtotal + adminFee;

            adminFeeTxt.innerText = 'Rp ' + adminFee.toLocaleString('id-ID');
            totalTxt.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');

            if (!adaItem) {
                container.innerHTML = `<div style="text-align: center; padding: 40px 0; opacity: 0.2; color: #fff;"><i class="fa-solid fa-basket-shopping fa-3x mb-3"></i><p>Belum ada menu dipilih</p></div>`;
                btnCart.disabled = true;
            } else {
                container.innerHTML = html;
                // Tombol hanya aktif jika ada item DAN warung buka
                btnCart.disabled = ("{{ $isOpen }}" == "1") ? false : true;
            }
        }

        async function gasAddToCart() {
            const name = document.getElementById('cust_name').value;
            const wa = document.getElementById('cust_wa').value;
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

            if (!name || !wa) {
                Swal.fire('Perhatian', 'Nama dan WhatsApp wajib diisi!', 'warning');
                return;
            }

            if ("{{ $isOpen }}" == "" || "{{ $isOpen }}" == "0") {
                Swal.fire('Maaf', 'Warung sudah tutup.', 'error');
                return;
            }

            // Munculkan loading
            Swal.fire({ 
                title: 'Memproses Pesanan...', 
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); } 
            });

            try {
                const response = await fetch("{{ route('orders.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        customer_name: name,
                        customer_whatsapp: wa,
                        payment_method: paymentMethod,
                        items: keranjangSementara
                    })
                });

                const result = await response.json();
                
                if(result.success) {
                    Swal.fire('Berhasil!', 'Pesanan Anda telah diterima.', 'success')
                    .then(() => {
                        if(paymentMethod === 'midtrans') {
                            // Redirect ke halaman checkout dengan parameter ID
                            // Menggunakan placeholder :id untuk direplace dengan order_id asli
                            let url = "{{ route('customer.checkout', [ 'id' => ':id' ]) }}";
                            window.location.href = url.replace(':id', result.order_id);
                        } else {
                            window.location.href = "{{ route('orders.index') }}";
                        }
                    });
                } else {
                    Swal.fire('Gagal', result.message || 'Terjadi kesalahan', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Gagal terhubung ke server. Cek koneksi internet dan konfigurasi Midtrans kamu.', 'error');
            }
        }
    </script>
@endsection