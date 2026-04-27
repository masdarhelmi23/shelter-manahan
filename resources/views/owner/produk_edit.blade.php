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
}

.form-container{
    width:100%;
    max-width:800px;
    font-family:'Inter',sans-serif;
}

/* =========================
   BACK LINK
========================= */
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
    flex-wrap:wrap;
}

.back-link:hover{
    color:#ffffff;
    transform:translateX(-5px);
}

/* =========================
   TITLE
========================= */
.judul-halaman{
    font-size:36px;
    font-weight:800;
    color:#ffffff;
    margin-bottom:10px;
    text-shadow:0 2px 10px rgba(0,0,0,.3);
    text-align:center;
    line-height:1.3;
}

.subjudul-teks{
    color:#cbd5e1;
    margin-bottom:35px;
    font-weight:600;
    text-align:center;
    line-height:1.7;
    word-break:break-word;
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
    box-shadow:0 25px 50px rgba(0,0,0,.3);
    width:100%;
}

/* =========================
   FORM
========================= */
.form-group{
    margin-bottom:25px;
}

.form-group label{
    display:block;
    font-size:12px;
    font-weight:800;
    color:#fcd34d;
    text-transform:uppercase;
    letter-spacing:1.5px;
    margin-bottom:12px;
    line-height:1.6;
}

.form-control{
    width:100%;
    padding:16px 20px;
    border-radius:15px;
    border:2px solid rgba(255,255,255,.1);
    background:rgba(0,0,0,.2);
    font-size:15px;
    color:#ffffff;
    transition:.3s;
    outline:none;
}

.form-control:focus{
    border-color:#0284c7;
    background:rgba(0,0,0,.4);
    box-shadow:0 0 15px rgba(2,132,199,.3);
}

select.form-control{
    appearance:none;
    cursor:pointer;
    color:#ffffff;
}

select.form-control option{
    background:#1e293b;
    color:#ffffff;
}

/* =========================
   GRID 2
========================= */
.form-grid-2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:25px;
}

/* =========================
   IMAGE PREVIEW
========================= */
.preview-area{
    display:flex;
    flex-direction:column;
    align-items:center;
    text-align:center;
}

.preview-img{
    width:140px;
    height:140px;
    border-radius:20px;
    object-fit:cover;
    margin-bottom:15px;
    border:3px solid rgba(255,255,255,.2);
    box-shadow:0 10px 20px rgba(0,0,0,.2);
}

.no-image{
    padding:30px;
    background:rgba(255,255,255,.05);
    border-radius:20px;
    color:#94a3b8;
    font-size:12px;
    margin-bottom:15px;
    border:2px dashed rgba(255,255,255,.1);
    width:100%;
    max-width:220px;
}

/* =========================
   BUTTON
========================= */
.btn-update{
    background:#0284c7;
    color:#ffffff;
    border:none;
    padding:18px 32px;
    border-radius:16px;
    font-weight:800;
    cursor:pointer;
    transition:.3s;
    width:100%;
    font-size:16px;
    margin-top:10px;
    box-shadow:0 10px 20px rgba(2,132,199,.3);
}

.btn-update:hover{
    background:#0ea5e9;
    transform:translateY(-3px);
    box-shadow:0 15px 30px rgba(2,132,199,.5);
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width:992px){

    .judul-halaman{
        font-size:30px;
    }

    .kartu-form{
        padding:30px;
    }
}

@media (max-width:768px){

    .form-wrapper-center{
        min-height:auto;
        padding:10px 0 25px;
    }

    .judul-halaman{
        font-size:26px;
    }

    .subjudul-teks{
        font-size:14px;
        margin-bottom:25px;
    }

    .kartu-form{
        padding:24px;
        border-radius:24px;
    }

    .form-grid-2{
        grid-template-columns:1fr;
        gap:0;
    }

    .form-control{
        padding:15px 16px;
        font-size:14px;
    }

    .btn-update{
        font-size:15px;
        padding:16px 18px;
    }

    .preview-img{
        width:120px;
        height:120px;
    }

    .back-link{
        font-size:12px;
    }
}

@media (max-width:480px){

    .judul-halaman{
        font-size:22px;
    }

    .subjudul-teks{
        font-size:13px;
    }

    .kartu-form{
        padding:18px;
        border-radius:20px;
    }

    .form-group label{
        font-size:11px;
        letter-spacing:1px;
    }

    .form-control{
        padding:14px;
        border-radius:12px;
    }

    .btn-update{
        font-size:14px;
        border-radius:14px;
    }

    .preview-img{
        width:100px;
        height:100px;
        border-radius:16px;
    }
}
</style>

<div class="form-wrapper-center">

    <div class="form-container">

        <a href="{{ route('owner.produk') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i>
            KEMBALI KE KATALOG
        </a>

        <h1 class="judul-halaman">Edit Produk</h1>

        <p class="subjudul-teks">
            Perbarui detail menu:
            <span style="color:#fcd34d;">{{ $product->nama_produk }}</span>
        </p>

        <div class="kartu-form">

            <form action="{{ route('owner.produk.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nama Produk -->
                <div class="form-group">
                    <label>Nama Produk / Menu</label>
                    <input
                        type="text"
                        name="nama_produk"
                        class="form-control"
                        value="{{ $product->nama_produk }}"
                        required
                    >
                </div>

                <!-- Harga + Status -->
                <div class="form-grid-2">

                    <div class="form-group">
                        <label>Harga Satuan (Rp)</label>
                        <input
                            type="number"
                            name="harga"
                            class="form-control"
                            value="{{ $product->harga }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Status Ketersediaan</label>

                        <select name="status" class="form-control" required>
                            <option value="aktif" {{ $product->status == 'aktif' ? 'selected' : '' }}>
                                Tersedia / Aktif
                            </option>

                            <option value="tidak aktif" {{ $product->status == 'tidak aktif' ? 'selected' : '' }}>
                                Habis / Non-Aktif
                            </option>
                        </select>
                    </div>

                </div>

                <!-- Foto -->
                <div class="form-group">
                    <label>Foto Produk Saat Ini</label>

                    <div class="preview-area">

                        @if($product->foto)
                            <img
                                src="{{ asset('storage/' . $product->foto) }}"
                                class="preview-img"
                                alt="Foto Produk"
                            >
                        @else
                            <div class="no-image">
                                Belum ada foto
                            </div>
                        @endif

                        <input
                            type="file"
                            name="foto"
                            class="form-control"
                            accept="image/*"
                        >

                        <small style="color:#94a3b8; margin-top:8px; font-weight:600; line-height:1.6;">
                            *Biarkan kosong jika tidak ingin mengganti foto
                        </small>

                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-update">
                    <i class="fa-solid fa-save" style="margin-right:8px;"></i>
                    SIMPAN PERUBAHAN DATA
                </button>

            </form>

        </div>

    </div>

</div>
@endsection