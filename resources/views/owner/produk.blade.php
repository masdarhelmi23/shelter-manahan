@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
*{
    box-sizing:border-box;
}

/* =========================
   HEADER
========================= */
.header-produk{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:35px;
    flex-wrap:wrap;
    gap:20px;
}

.judul-halaman{
    font-size:36px;
    font-weight:800;
    color:#ffffff;
    letter-spacing:-1px;
    text-shadow:0 2px 10px rgba(0,0,0,.3);
    line-height:1.3;
}

.subjudul-halaman{
    font-size:13px;
    color:#fcd34d;
    letter-spacing:3px;
    font-weight:700;
    text-transform:uppercase;
    margin-top:6px;
    line-height:1.6;
}

/* =========================
   BUTTON
========================= */
.btn-tambah{
    background:#0284c7;
    color:#ffffff;
    text-decoration:none;
    padding:14px 28px;
    border-radius:16px;
    font-weight:800;
    font-size:13px;
    transition:.3s;
    box-shadow:0 10px 20px rgba(2,132,199,.3);
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    border:1px solid rgba(255,255,255,.1);
    text-align:center;
}

.btn-tambah:hover{
    background:#0ea5e9;
    transform:translateY(-3px);
    box-shadow:0 15px 25px rgba(2,132,199,.4);
    color:#fff;
}

/* =========================
   TABLE CARD
========================= */
.kartu-tabel{
    background:rgba(255,255,255,.1);
    backdrop-filter:blur(15px);
    -webkit-backdrop-filter:blur(15px);
    border-radius:30px;
    padding:30px;
    border:1px solid rgba(255,255,255,.2);
    box-shadow:0 25px 50px rgba(0,0,0,.3);
    overflow:hidden;
}

/* DESKTOP TABLE */
.table-wrap{
    width:100%;
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:900px;
}

th{
    text-align:left;
    padding:20px;
    font-size:11px;
    text-transform:uppercase;
    color:#fcd34d;
    letter-spacing:2px;
    border-bottom:1px solid rgba(255,255,255,.1);
    font-weight:800;
}

td{
    padding:20px;
    font-size:14px;
    color:#ffffff;
    border-bottom:1px solid rgba(255,255,255,.05);
    vertical-align:middle;
}

/* =========================
   PRODUCT INFO
========================= */
.produk-info{
    display:flex;
    align-items:center;
    gap:15px;
}

.produk-img-wrapper{
    width:55px;
    height:55px;
    border-radius:14px;
    overflow:hidden;
    border:1px solid rgba(255,255,255,.2);
    background:rgba(0,0,0,.2);
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
}

.produk-img-wrapper img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.produk-icon-placeholder{
    color:#cbd5e1;
    font-size:20px;
}

.harga-text{
    font-weight:900;
    color:#ffffff;
    font-size:16px;
}

/* =========================
   BADGE
========================= */
.badge-status{
    padding:8px 16px;
    border-radius:12px;
    font-size:11px;
    font-weight:800;
    text-transform:uppercase;
    display:inline-block;
}

.status-aktif{
    background:rgba(34,197,94,.2);
    color:#4ade80;
    border:1px solid rgba(74,222,128,.3);
}

.status-nonaktif{
    background:rgba(239,68,68,.2);
    color:#f87171;
    border:1px solid rgba(248,113,113,.3);
}

/* =========================
   ACTION BUTTON
========================= */
.aksi-link{
    text-decoration:none;
    font-weight:800;
    font-size:11px;
    padding:10px 18px;
    border-radius:10px;
    transition:.2s;
    display:inline-block;
    letter-spacing:1px;
}

.edit{
    color:#38bdf8;
    background:rgba(56,189,248,.1);
    border:1px solid rgba(56,189,248,.2);
}

.edit:hover{
    background:#0284c7;
    color:#fff;
    box-shadow:0 5px 15px rgba(2,132,199,.4);
}

.hapus-form{
    display:inline;
}

.hapus-btn{
    color:#f87171;
    background:rgba(248,113,113,.1);
    border:1px solid rgba(248,113,113,.2);
    margin-left:5px;
    cursor:pointer;
    font-family:inherit;
    font-weight:800;
    font-size:11px;
    padding:10px 18px;
    border-radius:10px;
}

.hapus-btn:hover{
    background:#ef4444;
    color:#fff;
    box-shadow:0 5px 15px rgba(239,68,68,.4);
}

/* =========================
   FOOTER
========================= */
.footer-katalog{
    margin-top:30px;
    padding-top:25px;
    border-top:1px solid rgba(255,255,255,.1);
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    flex-wrap:wrap;
    color:#94a3b8;
    font-size:13px;
    font-weight:600;
}

/* =========================
   MOBILE CARD MODE
========================= */
.mobile-list{
    display:none;
}

.mobile-card{
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.08);
    border-radius:22px;
    padding:18px;
    margin-bottom:15px;
}

.mobile-top{
    display:flex;
    gap:14px;
    align-items:center;
    margin-bottom:15px;
}

.mobile-info h4{
    color:#fff;
    font-size:16px;
    margin-bottom:4px;
}

.mobile-info p{
    color:#94a3b8;
    font-size:12px;
}

.mobile-row{
    display:flex;
    justify-content:space-between;
    gap:10px;
    padding:8px 0;
    font-size:13px;
    border-top:1px solid rgba(255,255,255,.05);
}

.mobile-label{
    color:#94a3b8;
}

.mobile-value{
    color:#fff;
    font-weight:700;
    text-align:right;
}

.mobile-action{
    margin-top:15px;
    display:flex;
    gap:10px;
}

.mobile-action .aksi-link,
.mobile-action .hapus-btn{
    flex:1;
    margin:0;
    text-align:center;
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width:992px){

    .judul-halaman{
        font-size:30px;
    }

    .kartu-tabel{
        padding:22px;
    }
}

@media (max-width:768px){

    .header-produk{
        flex-direction:column;
        align-items:stretch;
    }

    .btn-tambah{
        width:100%;
    }

    .judul-halaman{
        font-size:26px;
    }

    .subjudul-halaman{
        font-size:11px;
        letter-spacing:2px;
    }

    .table-wrap{
        display:none;
    }

    .mobile-list{
        display:block;
    }

    .kartu-tabel{
        padding:18px;
        border-radius:24px;
    }

    .footer-katalog{
        flex-direction:column;
        align-items:flex-start;
    }
}

@media (max-width:480px){

    .judul-halaman{
        font-size:22px;
    }

    .mobile-action{
        flex-direction:column;
    }

    .btn-tambah{
        font-size:12px;
        padding:13px 16px;
    }
}
</style>

{{-- ALERT --}}
@if(session('success'))
<div style="background: rgba(34,197,94,.2); backdrop-filter: blur(10px); color:#4ade80; padding:20px; border-radius:20px; margin-bottom:30px; border:1px solid rgba(74,222,128,.3);">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- HEADER -->
<div class="header-produk">

    <div>
        <h1 class="judul-halaman">Katalog Produk</h1>
        <p class="subjudul-halaman">
            Manajemen menu warung {{ $shop->name ?? 'Anda' }}
        </p>
    </div>

    <a href="{{ route('owner.produk.create') }}" class="btn-tambah">
        <i class="fa-solid fa-plus"></i>
        TAMBAH MENU BARU
    </a>

</div>

<!-- TABLE / MOBILE -->
<div class="kartu-tabel">

    <!-- DESKTOP TABLE -->
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>INFORMASI PRODUK</th>
                <th>HARGA SATUAN</th>
                <th>STATUS MENU</th>
                <th>TANGGAL UPDATE</th>
                <th style="text-align:right;">PENGATURAN</th>
            </tr>
            </thead>

            <tbody>
            @forelse($produk ?? [] as $item)
            <tr>
                <td>
                    <div class="produk-info">

                        <div class="produk-img-wrapper">
                            @if($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_produk }}">
                            @else
                                <div class="produk-icon-placeholder">
                                    <i class="fa-solid fa-utensils"></i>
                                </div>
                            @endif
                        </div>

                        <div>
                            <div style="font-weight:800; font-size:16px;">
                                {{ $item->nama_produk }}
                            </div>

                            <div style="font-size:11px; color:#94a3b8; margin-top:2px; font-weight:600;">
                                REF-ID: #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>

                    </div>
                </td>

                <td>
                    <span class="harga-text">
                        Rp {{ number_format($item->harga,0,',','.') }}
                    </span>
                </td>

                <td>
                    @if($item->status == 'aktif')
                        <span class="badge-status status-aktif">Tersedia</span>
                    @else
                        <span class="badge-status status-nonaktif">Habis / Off</span>
                    @endif
                </td>

                <td>
                    <span style="font-size:13px; color:#cbd5e1; font-weight:700;">
                        {{ $item->updated_at->format('d M Y') }}
                    </span>
                </td>

                <td style="text-align:right; white-space:nowrap;">

                    <a href="{{ route('owner.produk.edit', $item->id) }}" class="aksi-link edit">
                        EDIT
                    </a>

                    <form action="{{ route('owner.produk.destroy', $item->id) }}"
                          method="POST"
                          class="hapus-form"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="hapus-btn">
                            HAPUS
                        </button>
                    </form>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:80px; color:#cbd5e1;">
                    <i class="fa-solid fa-box-open" style="font-size:50px; margin-bottom:20px; display:block; opacity:.3;"></i>
                    <span style="font-style:italic; font-weight:600;">
                        Belum ada data produk dalam database warung Anda.
                    </span>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- MOBILE CARD -->
    <div class="mobile-list">

        @forelse($produk ?? [] as $item)

        <div class="mobile-card">

            <div class="mobile-top">

                <div class="produk-img-wrapper">
                    @if($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_produk }}">
                    @else
                        <div class="produk-icon-placeholder">
                            <i class="fa-solid fa-utensils"></i>
                        </div>
                    @endif
                </div>

                <div class="mobile-info">
                    <h4>{{ $item->nama_produk }}</h4>
                    <p>REF-ID #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</p>
                </div>

            </div>

            <div class="mobile-row">
                <span class="mobile-label">Harga</span>
                <span class="mobile-value">Rp {{ number_format($item->harga,0,',','.') }}</span>
            </div>

            <div class="mobile-row">
                <span class="mobile-label">Status</span>
                <span class="mobile-value">
                    @if($item->status == 'aktif')
                        <span class="badge-status status-aktif">Tersedia</span>
                    @else
                        <span class="badge-status status-nonaktif">Habis</span>
                    @endif
                </span>
            </div>

            <div class="mobile-row">
                <span class="mobile-label">Update</span>
                <span class="mobile-value">{{ $item->updated_at->format('d M Y') }}</span>
            </div>

            <div class="mobile-action">

                <a href="{{ route('owner.produk.edit', $item->id) }}" class="aksi-link edit">
                    EDIT
                </a>

                <form action="{{ route('owner.produk.destroy', $item->id) }}"
                      method="POST"
                      style="flex:1;"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="hapus-btn" style="width:100%;">
                        HAPUS
                    </button>
                </form>

            </div>

        </div>

        @empty

        <div style="text-align:center; padding:40px 15px; color:#cbd5e1;">
            <i class="fa-solid fa-box-open" style="font-size:42px; margin-bottom:15px; display:block; opacity:.3;"></i>
            Belum ada data produk dalam database warung Anda.
        </div>

        @endforelse

    </div>

</div>

<!-- FOOTER -->
<div class="footer-katalog">

    <div>
        TOTAL ENTITAS:
        <span style="color:#ffffff;">
            {{ $produk ? $produk->count() : 0 }} Produk
        </span>
    </div>

   

</div>

@endsection