@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* =========================
        BASE & KPI STYLING
    ========================== */
    .judul-halaman { font-size: 32px; font-weight: 800; color: #ffffff; margin-bottom: 5px; letter-spacing: -1px; }
    .subjudul-halaman { font-size: 12px; color: #fcd34d; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; margin-bottom: 25px; display: block; }

    .status-bar {
        background: rgba(255, 255, 255, 0.05); border-radius: 25px; padding: 20px 35px;
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(15px);
    }

    .btn-action-status { padding: 12px 20px; border-radius: 12px; font-weight: 800; border: none; transition: 0.3s; cursor: pointer; text-transform: uppercase; font-size: 12px; }
    .btn-tutup { background: #ef4444; color: #fff; }
    .btn-buka { background: #22c55e; color: #fff; }

    .grid-kpi { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 25px; }
    .kartu-mewah { 
        background: rgba(255, 255, 255, 0.07); backdrop-filter: blur(15px); border-radius: 25px; 
        padding: 30px; border: 1px solid rgba(255, 255, 255, 0.1); transition: 0.4s; 
    }
    .label-kpi { font-size: 10px; font-weight: 800; color: #fcd34d; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 10px; display: block; }
    .nilai-kpi { font-size: 32px; font-weight: 900; color: #fff; line-height: 1; }

    /* FORM PENDAFTARAN TOKO (STYLE PENGATURAN) */
    .form-group-mewah { margin-bottom: 20px; }
    .form-group-mewah label { display: block; color: #fcd34d; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px; }
    .input-mewah {
        width: 100%; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1);
        color: white; border-radius: 12px; padding: 15px; outline: none; transition: 0.3s;
    }
    .input-mewah:focus { border-color: #fcd34d; background: rgba(0, 0, 0, 0.5); }

    .upload-logo-wrapper {
        display: flex; align-items: center; gap: 20px; margin-bottom: 25px;
        padding: 20px; background: rgba(255,255,255,0.03); border-radius: 18px; border: 1px dashed rgba(255,255,255,0.2);
    }
    .preview-logo-box { width: 80px; height: 80px; border-radius: 15px; background: #000; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 2px solid #fcd34d; }
    .preview-logo-box img { width: 100%; height: 100%; object-fit: cover; }

    /* =========================================
        FIXED POPUP
    ========================================= */
    #popupLibur {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.92); backdrop-filter: blur(10px);
        display: none; justify-content: center; align-items: center;
        z-index: 999999; padding: 20px;
    }
    .popup-box {
        background: #111827; width: 100%; max-width: 400px; padding: 40px;
        border-radius: 35px; border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 1); position: relative; text-align: center;
    }
    .input-hari-mewah {
        width: 100%; background: #000; border: 2px solid rgba(255,255,255,0.1);
        color: #fcd34d; border-radius: 18px; padding: 20px; font-weight: 800;
        font-size: 28px; text-align: center; margin-top: 15px; outline: none;
    }

    @media (max-width: 768px) { 
        .status-bar { flex-direction: column; gap: 20px; text-align: center; } 
        .grid-pendaftaran { grid-template-columns: 1fr !important; }
    }
</style>

@if(!$shop)
    <div class="judul-halaman" style="text-align: center; margin-top: 30px;">Aktivasi Warung</div>
    <span class="subjudul-halaman" style="text-align: center;">Lengkapi identitas bisnis Shelter Manahan Anda</span>

    <div class="kartu-mewah" style="max-width: 800px; margin: 0 auto 50px;">
        <form action="{{ route('owner.toko.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="upload-logo-wrapper">
                <div class="preview-logo-box" id="logoPreview">
                    <i class="fas fa-image" style="color: #475569; font-size: 24px;"></i>
                </div>
                <div style="flex: 1;">
                    <label style="display: block; color: #fff; font-size: 13px; font-weight: 700; margin-bottom: 8px;">Logo / Foto Warung</label>
                    <input type="file" name="logo" class="input-mewah" accept="image/*" onchange="previewImage(this)" required>
                    <small style="color: #64748b; font-size: 10px; margin-top: 5px; display: block;">* Format: JPG, PNG (Maks. 2MB)</small>
                </div>
            </div>

            <div class="form-group-mewah">
                <label>Nama Toko / Shelter NO..</label>
                <input type="text" name="name" class="input-mewah" placeholder="Contoh: Shelter 1 'Bakso Kawi'" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;" class="grid-pendaftaran">
                <div class="form-group-mewah">
                    <label>Nomor WhatsApp (Contoh: 628123XXX)</label>
                    <input type="number" name="whatsapp" class="input-mewah" placeholder="628..." required>
                </div>
                <div class="form-group-mewah">
                    <label>Username Instagram (Tanpa @)</label>
                    <input type="text" name="instagram" class="input-mewah" placeholder="nama_toko_ig">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;" class="grid-pendaftaran">
                <div class="form-group-mewah">
                    <label><i class="far fa-clock"></i> Jam Buka</label>
                    <input type="time" name="open_time" class="input-mewah" value="08:00" required>
                </div>
                <div class="form-group-mewah">
                    <label><i class="far fa-clock"></i> Jam Tutup</label>
                    <input type="time" name="close_time" class="input-mewah" value="22:00" required>
                </div>
            </div>

            <button type="submit" class="btn-action-status btn-buka" style="width:100%; padding: 18px; margin-top: 10px; font-size: 14px;">
                <i class="fas fa-rocket"></i> DAFTARKAN WARUNG SEKARANG
            </button>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('logoPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview">`;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

@else
    @php
        $sekarang = \Carbon\Carbon::now();
        $jamBuka = \Carbon\Carbon::parse($shop->open_time);
        $jamTutup = \Carbon\Carbon::parse($shop->close_time);
        $isJamOperasional = $sekarang->between($jamBuka, $jamTutup);
        $sedangLibur = $shop->closed_until && $sekarang->lessThanOrEqualTo(\Carbon\Carbon::parse($shop->closed_until));
        $isWarungBuka = $isJamOperasional && !$sedangLibur;
    @endphp

    <div class="judul-halaman">Dashboard Owner</div>
    <span class="subjudul-halaman">{{ $shop->name }}</span>

    <div class="status-bar">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; border-radius: 15px; display: flex; align-items: center; justify-content: center; background: {{ $isWarungBuka ? 'rgba(34, 197, 94, 0.2)' : 'rgba(239, 68, 68, 0.2)' }}; border: 1px solid {{ $isWarungBuka ? '#4ade80' : '#f87171' }};">
                <i class="fas {{ $isWarungBuka ? 'fa-store' : 'fa-store-slash' }} fa-lg" style="color: {{ $isWarungBuka ? '#4ade80' : '#f87171' }};"></i>
            </div>
            <div style="text-align: left;">
                <div style="font-weight: 800; font-size: 20px; color: {{ $isWarungBuka ? '#4ade80' : '#f87171' }}; line-height: 1;">
                    {{ $isWarungBuka ? 'WARUNG BUKA' : 'WARUNG TUTUP' }}
                </div>
                <div style="font-size: 11px; color: #94a3b8; font-weight: 600; margin-top: 5px;">
                    @if($sedangLibur) 
                        <span style="color:#fbbf24;"><i class="fas fa-calendar"></i> LIBUR S/D {{ \Carbon\Carbon::parse($shop->closed_until)->format('d M Y') }}</span>
                    @else
                        <i class="fas fa-clock"></i> JADWAL: {{ $jamBuka->format('H:i') }} - {{ $jamTutup->format('H:i') }}
                    @endif
                </div>
            </div>
        </div>
        
        @if($sedangLibur)
            <form action="{{ route('owner.libur.update') }}" method="POST" style="display:inline;">
                @csrf <input type="hidden" name="action" value="buka">
                <button type="submit" class="btn-action-status btn-buka">BUKA SEKARANG</button>
            </form>
        @else
            <button type="button" class="btn-action-status btn-tutup" onclick="togglePopup(true)">SET LIBUR / TUTUP</button>
        @endif
    </div>

    <div class="grid-kpi">
        <div class="kartu-mewah">
            <span class="label-kpi">Total Pendapatan</span>
            <div class="nilai-kpi">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="kartu-mewah">
            <span class="label-kpi">Pesanan Sukses</span>
            <div class="nilai-kpi" style="color: #38bdf8;">{{ $totalPesanan ?? 0 }} <span style="font-size: 14px;">Order</span></div>
        </div>
    </div>

    <div class="kartu-mewah">
        <span class="label-kpi"><i class="fas fa-crown"></i> Menu Paling Laris</span>
        <div style="margin-top: 20px;">
            @if($menuPopuler)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 15px; background: rgba(255,255,255,0.03); border-radius: 15px; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="display: flex; align-items: center;">
                        <div style="width: 30px; height: 30px; background: #0284c7; color: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; margin-right: 15px;">1</div>
                        <div style="color: #fff; font-weight: 700;">{{ $menuPopuler->nama_produk }}</div>
                    </div>
                    <div style="color: #4ade80; font-weight: 800;">{{ $menuPopuler->total_sold }} Terjual</div>
                </div>
            @else
                <p style="color: #64748b; font-size: 13px;">Belum ada data penjualan.</p>
            @endif
        </div>
    </div>

    <!-- POPUP LIBUR -->
    <div id="popupLibur">
        <div class="popup-box">
            <span style="position: absolute; top: 20px; right: 25px; color: #64748b; font-size: 22px; cursor: pointer;" onclick="togglePopup(false)">&times;</span>
            <div style="background: rgba(239, 68, 68, 0.1); width: 60px; height: 60px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="fas fa-calendar-day" style="color: #ef4444; font-size: 26px;"></i>
            </div>
            <h3 style="color: #fff; font-weight: 800; margin-bottom: 5px;">Atur Masa Libur</h3>
            <p style="color: #94a3b8; font-size: 13px;">Berapa hari warung ingin diliburkan?</p>

            <form action="{{ route('owner.libur.update') }}" method="POST">
                @csrf
                <div style="margin: 25px 0;">
                    <input type="number" name="durasi" class="input-hari-mewah" placeholder="0" min="0" required autofocus>
                    <div style="color: #fcd34d; font-size: 11px; font-weight: 700; margin-top: 10px; text-transform: uppercase; letter-spacing: 1.5px;">Jumlah Hari</div>
                </div>
                
                <button type="submit" class="btn-action-status btn-tutup" style="width: 100%; padding: 18px;">KONFIRMASI LIBUR</button>
            </form>
        </div>
    </div>

    <script>
        function togglePopup(show) {
            const popup = document.getElementById('popupLibur');
            popup.style.display = show ? 'flex' : 'none';
        }
        window.onclick = function(event) {
            const popup = document.getElementById('popupLibur');
            if (event.target == popup) togglePopup(false);
        }
    </script>
@endif
@endsection