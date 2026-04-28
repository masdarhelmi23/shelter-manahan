<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan | Shelter Manahan</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary: #0284c7;
            --primary-soft: rgba(2, 132, 199, 0.08);
            --bg-topbar: #f1f5f9;
            --teks-gelap: #1e293b;
            --teks-abu: #64748b;
            --merah-logout: #ef4444;
            --white: #ffffff;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body { background: #000; min-height: 100vh; overflow-x: hidden; }

        .page-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: 
                linear-gradient(to bottom, rgba(0, 0, 0, .85), rgba(0, 0, 0, .75)),
                url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=2074&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* NAVBAR - IDENTIK DENGAN WARUNG */
        .navbar {
            height: 80px; background: var(--bg-topbar);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%; position: sticky; top: 0; z-index: 1001;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }

        .brand {
            text-decoration: none; font-size: 20px; font-weight: 800;
            color: var(--primary); letter-spacing: 1.5px; text-transform: uppercase;
        }
        .brand span { color: var(--teks-gelap); font-weight: 400; }

        /* CHECKOUT CONTAINER */
        .container {
            width: 90%; max-width: 1200px;
            margin: 40px auto; flex: 1;
            display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px;
        }

        /* CARD STYLE - GLASSMORPHISM */
        .checkout-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px; padding: 35px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.5);
        }

        .section-title {
            font-size: 22px; font-weight: 800; color: var(--teks-gelap);
            margin-bottom: 25px; display: flex; align-items: center; gap: 12px;
        }

        /* PRODUCT INFO SECTION */
        .product-preview {
            display: flex; gap: 20px; align-items: center;
            padding: 20px; background: #f8fafc; border-radius: 20px;
            margin-bottom: 30px; border: 1px solid #e2e8f0;
        }

        .product-img {
            width: 100px; height: 100px; border-radius: 16px;
            object-fit: cover; box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .product-detail h3 { font-size: 18px; color: var(--teks-gelap); margin-bottom: 5px; }
        .product-detail p { font-size: 16px; color: var(--primary); font-weight: 700; }

        /* FORM STYLING */
        .form-label { display: block; font-size: 13px; font-weight: 700; color: var(--teks-abu); margin-bottom: 8px; text-transform: uppercase; }
        .input-group { margin-bottom: 20px; }
        .form-input {
            width: 100%; padding: 14px 18px; border-radius: 14px;
            border: 1px solid #e2e8f0; outline: none; transition: var(--transition-smooth);
            font-size: 15px; background: #fff;
        }
        .form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px var(--primary-soft); }

        /* ORDER SUMMARY (Right Column) */
        .summary-row {
            display: flex; justify-content: space-between; margin-bottom: 15px;
            font-size: 15px; color: var(--teks-abu);
        }
        .summary-total {
            border-top: 2px dashed #e2e8f0; margin-top: 20px; padding-top: 20px;
            display: flex; justify-content: space-between;
            font-size: 20px; font-weight: 800; color: var(--teks-gelap);
        }

        /* BUTTONS */
        .btn-pay {
            width: 100%; padding: 18px; border-radius: 18px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff; font-size: 15px; font-weight: 800;
            border: none; cursor: pointer; transition: var(--transition-smooth);
            display: flex; align-items: center; justify-content: center; gap: 12px;
            text-transform: uppercase; margin-top: 30px;
        }
        .btn-pay:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(34, 197, 94, 0.4); filter: brightness(1.1); }

        .btn-cancel {
            display: block; text-align: center; margin-top: 15px;
            color: var(--teks-abu); text-decoration: none; font-size: 13px; font-weight: 600;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .container { grid-template-columns: 1fr; margin-top: 20px; }
            .checkout-card { padding: 25px; }
        }

        @media (max-width: 600px) {
            .navbar { padding: 0 20px; }
            .section-title { font-size: 18px; }
            .brand { font-size: 18px; }
        }
    </style>
</head>
<body>

<div class="page-container">

    <nav class="navbar">
        <a href="/" class="brand">SHELTER <span>MANAHAN</span></a>
        <div style="display: flex; align-items: center; gap: 15px;">
            <span style="font-weight: 700; font-size: 13px; color: var(--teks-gelap);">{{ auth()->user()->name }}</span>
            <i class="fa-solid fa-circle-check" style="color: var(--primary);"></i>
        </div>
    </nav>

    <div class="container">
        
        <div class="checkout-card">
            <h2 class="section-title">
                <i class="fa-solid fa-user-pen" style="color: var(--primary);"></i> Detail Pengiriman
            </h2>

            <form action="{{ route('checkout.pay') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="amount" value="{{ $product->harga }}">

                <div class="input-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="customer_name" class="form-input" value="{{ auth()->user()->name }}" readonly>
                </div>

                <div class="input-group">
                    <label class="form-label">Nomor WhatsApp (Aktif)</label>
                    <input type="text" name="phone" class="form-input" placeholder="Contoh: 081234567xxx" required>
                </div>

                <div class="input-group">
                    <label class="form-label">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" class="form-input" style="height: 100px; resize: none;" placeholder="Contoh: Sambalnya dipisah ya.."></textarea>
                </div>

                <h2 class="section-title" style="margin-top: 40px;">
                    <i class="fa-solid fa-wallet" style="color: var(--primary);"></i> Metode Pembayaran
                </h2>
                <div style="padding: 15px; border: 2px solid var(--primary-soft); border-radius: 15px; display: flex; align-items: center; gap: 15px; background: var(--primary-soft);">
                    <i class="fa-solid fa-shield-halved fa-2x" style="color: var(--primary);"></i>
                    <div>
                        <p style="font-weight: 800; font-size: 14px; color: var(--teks-gelap);">Sistem Pembayaran Terintegrasi</p>
                        <p style="font-size: 12px; color: var(--teks-abu);">Pembayaran otomatis diverifikasi oleh sistem.</p>
                    </div>
                </div>
            </form>
        </div>

        <div class="checkout-card" style="height: fit-content;">
            <h2 class="section-title">Ringkasan Pesanan</h2>
            
            <div class="product-preview">
                @if($product->foto)
                    <img src="{{ asset('storage/' . $product->foto) }}" class="product-img" alt="Produk">
                @else
                    <div style="width: 100px; height: 100px; background: #e2e8f0; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-bowl-food fa-2x" style="color: #94a3b8;"></i>
                    </div>
                @endif
                <div class="product-detail">
                    <h3>{{ $product->nama_produk }}</h3>
                    <p>Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Biaya Layanan</span>
                <span style="color: #22c55e;">Gratis</span>
            </div>

            <div class="summary-total">
                <span>Total Bayar</span>
                <span style="color: var(--primary);">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
            </div>

            <button type="submit" form="checkoutForm" class="btn-pay">
                <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
            </button>

            <a href="{{ url()->previous() }}" class="btn-cancel">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
            </a>
        </div>

    </div>

    <footer style="text-align:center; padding:40px; color:#94a3b8; font-size:11px;">
        &copy; {{ date('Y') }} <b>Shelter Manahan</b> &bull; Secure Executive Payment System
    </footer>

</div>

</body>
</html>