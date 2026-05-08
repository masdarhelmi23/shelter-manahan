@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
*{
    box-sizing:border-box;
}

/* WRAPPER */
.form-wrapper-center{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    min-height:80vh;
    padding:25px 0;
}

.form-container{
    width:100%;
    max-width:800px;
    font-family:'Inter',sans-serif;
}

/* BACK */
.back-link{
    text-decoration:none;
    color:#fcd34d;
    font-weight:800;
    font-size:13px;
    display:inline-flex;
    align-items:center;
    gap:8px;
    margin-bottom:25px;
    transition:.3s;
    text-transform:uppercase;
    letter-spacing:1px;
}

.back-link:hover{
    color:#ffffff;
    transform:translateX(-5px);
}

/* TITLE */
.judul-halaman{
    font-size:36px;
    font-weight:800;
    color:#ffffff;
    margin-bottom:10px;
    text-align:center;
}

.subjudul-teks{
    color:#cbd5e1;
    margin-bottom:35px;
    font-weight:600;
    text-align:center;
}

/* CARD */
.kartu-form{
    background:rgba(255,255,255,.1);
    backdrop-filter:blur(15px);
    border-radius:30px;
    padding:40px;
    border:1px solid rgba(255,255,255,.2);
}

/* FORM */
.form-group{
    margin-bottom:25px;
}

.form-group label{
    display:block;
    font-size:12px;
    font-weight:800;
    color:#fcd34d;
    text-transform:uppercase;
    margin-bottom:12px;
}

.form-control{
    width:100%;
    padding:16px;
    border-radius:15px;
    border:2px solid rgba(255,255,255,.1);
    background:rgba(0,0,0,.2);
    color:#ffffff;
    transition:.3s;
    outline:none;
}

.form-control:focus{
    border-color:#0284c7;
}

/* BUTTON */
.btn-simpan{
    background:#0284c7;
    color:#ffffff;
    border:none;
    padding:18px;
    border-radius:16px;
    font-weight:800;
    cursor:pointer;
    width:100%;
    margin-top:10px;
}

.btn-simpan:hover{
    background:#0ea5e9;
}
</style>

<div class="form-wrapper-center">

    <div class="form-container">

        <a href="{{ route('owner.produk') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i>
            KEMBALI KE KATALOG
        </a>

        <h1 class="judul-halaman">Tambah Produk Baru</h1>

        <p class="subjudul-teks">
            Daftarkan menu andalan untuk warung
            <span style="color:#fcd34d;">{{ $shop->name ?? 'Anda' }}</span>
        </p>

        <div class="kartu-form">

            <form action="{{ route('owner.produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- NAMA -->
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk') }}">
                    @error('nama_produk') 
                        <small style="color: #fb7185; font-weight: bold;">* {{ $message }}</small> 
                    @enderror
                </div>

                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" class="form-control" value="{{ old('harga') }}">
                    @error('harga') 
                        <small style="color: #fb7185; font-weight: bold;">* {{ $message }}</small> 
                    @enderror
                </div>

                <div class="form-group">
                    <label>Foto Produk</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    @error('foto') 
                        <small style="color: #fb7185; font-weight: bold;">* {{ $message }}</small> 
                    @enderror
                </div>

                <button type="submit" class="btn-simpan">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    DAFTARKAN KE KATALOG
                </button>

            </form>

        </div>

    </div>

</div>

{{-- ================= POPUP ERROR INDONESIA ================= --}}
@if ($errors->any())
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Data Belum Lengkap!',
        confirmButtonText: 'Mengerti',
        confirmButtonColor: '#0284c7',
        background: '#0f172a',
        color: '#ffffff'
    });
</script>
@endif

@endsection