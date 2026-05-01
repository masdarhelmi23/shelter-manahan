@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
*{
    box-sizing:border-box;
}

/* =========================
   WRAPPER
========================= */
.form-wrapper-center{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    min-height:80vh;
    padding:20px 0;
    width:100%;
}

/* =========================
   TITLE
========================= */
.judul-halaman{
    font-size:32px;
    font-weight:800;
    color:#ffffff;
    text-align:center;
    margin-bottom:30px;
    line-height:1.4;
    padding:0 10px;
}

/* =========================
   ALERT
========================= */
.alert-success{
    background:rgba(34,197,94,.2);
    color:#4ade80;
    padding:15px;
    border-radius:15px;
    margin-bottom:20px;
    width:100%;
    max-width:900px;
    border:1px solid rgba(74,222,128,.3);
    font-weight:700;
}

/* =========================
   CARD
========================= */
.kartu-form{
    background:rgba(255,255,255,.1);
    backdrop-filter:blur(15px);
    -webkit-backdrop-filter:blur(15px);
    border-radius:30px;
    padding:40px;
    border:1px solid rgba(255,255,255,.2);
    width:100%;
    max-width:900px;
}

/* =========================
   SECTION TITLE
========================= */
.section-title{
    color:#ffffff;
    font-size:18px;
    font-weight:700;
    margin:20px 0;
    border-bottom:1px solid rgba(255,255,255,.1);
    padding-bottom:10px;
    line-height:1.5;
}

/* =========================
   FORM
========================= */
.form-group{
    width:100%;
}

.form-group label{
    display:block;
    font-size:11px;
    font-weight:800;
    color:#fcd34d;
    text-transform:uppercase;
    margin-bottom:10px;
    letter-spacing:1px;
    line-height:1.6;
}

.form-control{
    width:100%;
    padding:14px;
    border-radius:12px;
    border:2px solid rgba(255,255,255,.1);
    background:rgba(0,0,0,.2);
    color:#ffffff;
    outline:none;
    transition:.3s;
    font-size:14px;
}

.form-control:focus{
    border-color:#0284c7;
    background:rgba(0,0,0,.4);
}

.form-control::placeholder{
    color:#94a3b8;
}

/* =========================
   GRID
========================= */
.grid-2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.mt-15{
    margin-top:15px;
}

.mt-40{
    margin-top:40px;
}

/* =========================
   LOGO BOX
========================= */
.logo-box{
    display:flex;
    align-items:center;
    gap:25px;
    margin-bottom:25px;
    background:rgba(0,0,0,.2);
    padding:20px;
    border-radius:20px;
    border:1px solid rgba(255,255,255,.05);
}

.logo-preview-wrap{
    text-align:center;
    flex-shrink:0;
}

.logo-preview-wrap label{
    display:block;
    margin-bottom:10px;
    color:#fcd34d;
    font-size:11px;
    font-weight:800;
    text-transform:uppercase;
}

.logo-preview{
    width:100px;
    height:100px;
    border-radius:20px;
    overflow:hidden;
    border:2px solid #fcd34d;
    background:rgba(255,255,255,.05);
    display:flex;
    align-items:center;
    justify-content:center;
}

.logo-preview img{
    width:100%;
    height:100%;
    object-fit:cover;
}

/* =========================
   BUTTON
========================= */
.btn-simpan{
    background:#0284c7;
    color:white;
    border:none;
    padding:16px;
    border-radius:15px;
    font-weight:800;
    width:100%;
    cursor:pointer;
    margin-top:25px;
    transition:.3s;
    font-size:15px;
}

.btn-simpan:hover{
    background:#0ea5e9;
    transform:translateY(-3px);
}

small{
    color:#94a3b8;
    display:block;
    margin-top:6px;
    line-height:1.6;
}

/* =========================
   RESPONSIVE TABLET
========================= */
@media (max-width:992px){
    .judul-halaman{
        font-size:28px;
    }
    .kartu-form{
        padding:30px;
    }
}

/* =========================
   RESPONSIVE MOBILE
========================= */
@media (max-width:768px){
    .form-wrapper-center{
        min-height:auto;
        padding:10px 0 25px;
    }
    .judul-halaman{
        font-size:24px;
        margin-bottom:20px;
    }
    .kartu-form{
        padding:22px;
        border-radius:24px;
    }
    .grid-2{
        grid-template-columns:1fr;
        gap:15px;
    }
    .logo-box{
        flex-direction:column;
        align-items:stretch;
        gap:18px;
        padding:18px;
    }
    .logo-preview-wrap{
        text-align:center;
    }
    .logo-preview{
        margin:0 auto;
        width:90px;
        height:90px;
    }
    .btn-simpan{
        padding:15px;
        font-size:14px;
    }
}

/* =========================
   EXTRA SMALL
========================= */
@media (max-width:480px){
    .judul-halaman{
        font-size:21px;
    }
    .kartu-form{
        padding:18px;
        border-radius:20px;
    }
    .section-title{
        font-size:16px;
    }
    .form-group label{
        font-size:10px;
        letter-spacing:.8px;
    }
    .form-control{
        padding:13px;
        font-size:13px;
        border-radius:10px;
    }
    .btn-simpan{
        border-radius:12px;
    }
}
</style>

<div class="form-wrapper-center">

    <h1 class="judul-halaman">Pengaturan Profil & Toko</h1>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="kartu-form">

        <form action="{{ route('owner.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- ===================== -->
            <!-- INFORMASI WARUNG -->
            <!-- ===================== -->
            <div class="section-title">
                <i class="fas fa-store"></i> Informasi Warung
            </div>

            <div class="logo-box">
                <div class="logo-preview-wrap">
                    <label>Logo Saat Ini</label>
                    <div class="logo-preview">
                        @if($shop->logo)
                            <img src="{{ asset('storage/' . $shop->logo) }}">
                        @else
                            <i class="fas fa-image fa-2x" style="color:rgba(255,255,255,.2);"></i>
                        @endif
                    </div>
                </div>

                <div style="flex:1;">
                    <label style="color:#ffffff;">Ganti Logo / Foto Toko</label>
                    <input type="file" name="logo" class="form-control text-white" accept="image/*">
                    <small style="color:#cbd5e1;">*Format: JPG, PNG (Maks. 2MB)</small>
                </div>
            </div>

            <div class="form-group">
                <label>Nama Toko / Warung</label>
                <input type="text" name="nama_toko" class="form-control" value="{{ $shop->name }}" required>
            </div>

            <div class="grid-2 mt-15">
                <div class="form-group">
                    <label>Nomor WhatsApp (Contoh: 628123xxx)</label>
                    <input type="text" name="whatsapp" class="form-control" value="{{ $shop->whatsapp }}" placeholder="628...">
                </div>

                <div class="form-group">
                    <label>Username Instagram (Tanpa @)</label>
                    <input type="text" name="instagram" class="form-control" value="{{ $shop->instagram }}" placeholder="nama_toko_ig">
                </div>
            </div>

            <!-- PENAMBAHAN JAM OPERASIONAL (REVISED VALUE FORMAT) -->
            <div class="grid-2 mt-15">
                <div class="form-group">
                    <label><i class="fas fa-clock"></i> Jam Buka</label>
                    {{-- Kita format nilainya ke H:i agar dikenali input type="time" --}}
                    <input type="time" name="jam_buka" class="form-control" 
                        value="{{ $shop->open_time ? \Carbon\Carbon::parse($shop->open_time)->format('H:i') : '' }}">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-moon"></i> Jam Tutup</label>
                    <input type="time" name="jam_tutup" class="form-control" 
                        value="{{ $shop->close_time ? \Carbon\Carbon::parse($shop->close_time)->format('H:i') : '' }}">
                </div>
            </div>

            <!-- ===================== -->
            <!-- AKUN -->
            <!-- ===================== -->
            <div class="section-title mt-40">
                <i class="fas fa-user-shield"></i> Akun Pengelola
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Username Login</label>
                    <input type="text" name="username" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="form-group">
                    <label>Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
            </div>

            <div class="grid-2 mt-15">
                <div class="form-group">
                    <label>Password Baru (Kosongkan jika tidak ganti)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn-simpan">
                SIMPAN PERUBAHAN PROFIL
            </button>

        </form>
    </div>
</div>
@endsection