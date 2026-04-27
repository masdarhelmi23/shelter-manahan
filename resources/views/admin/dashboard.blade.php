@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* Tipografi Utama - Kontras Tinggi */
    .judul-halaman { 
        font-size: 36px; 
        font-weight: 800; 
        color: #ffffff; 
        margin-bottom: 5px; 
        letter-spacing: -1px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    
    .subjudul-halaman { 
        font-size: 13px; 
        color: #fcd34d; /* Emas */
        letter-spacing: 3px; 
        text-transform: uppercase; 
        font-weight: 700;
        margin-bottom: 40px; 
        display: block;
    }

    .grid-kpi {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
    }

    /* Kartu Mewah - Glassmorphism */
    .kartu-mewah {
        background: rgba(255, 255, 255, 0.1); 
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 30px;
        padding: 35px;
        position: relative;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); 
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s ease;
    }

    .kartu-mewah:hover {
        transform: translateY(-10px);
        border-color: #fcd34d;
        background: rgba(255, 255, 255, 0.15);
    }

    .label-kpi { 
        font-size: 12px; 
        font-weight: 800; 
        color: #e2e8f0; 
        letter-spacing: 2px; 
        display: block; 
        margin-bottom: 20px; 
        text-transform: uppercase;
    }

    .nilai-kpi { 
        font-size: 56px; 
        font-weight: 900; 
        color: #ffffff; 
        line-height: 1; 
    }

    /* Indikator Jalur Neon */
    .indikator-jalur { 
        height: 8px; 
        width: 100%; 
        background: rgba(255,255,255,0.1); 
        border-radius: 20px; 
        margin-top: 25px; 
        overflow: hidden;
    }
    
    .indikator-isi { 
        height: 100%; 
        background: linear-gradient(90deg, #0284c7, #38bdf8); 
        box-shadow: 0 0 15px rgba(2, 132, 199, 0.5); 
        border-radius: 20px; 
    }

    /* Panel Wawasan Glass */
    .panel-wawasan {
        margin-top: 40px;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(10px);
        border-radius: 30px;
        padding: 35px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 8px solid #0284c7;
    }

    .judul-wawasan {
        font-size: 14px; 
        font-weight: 800;
        color: #fcd34d; 
        letter-spacing: 2px; 
        margin-bottom: 20px;
        text-transform: uppercase;
    }
</style>

<div class="judul-halaman">Ringkasan Eksekutif</div>
<span class="subjudul-halaman">Analisis Real-Time Shelter Manahan</span>

<div class="grid-kpi">
    <div class="kartu-mewah">
        <span class="label-kpi">Entitas Pengguna</span>
        <div class="nilai-kpi">{{ $totalUser }}</div>
        <div class="indikator-jalur">
            <div class="indikator-isi" style="width: 70%;"></div>
        </div>
        <p style="font-size: 12px; color: #94a3b8; margin-top: 15px; font-weight: 600;">Data akun terverifikasi sistem</p>
    </div>

    <div class="kartu-mewah">
        <span class="label-kpi">Okupansi Komersial</span>
        <div class="nilai-kpi">{{ $totalToko }}</div>
        <div class="indikator-jalur">
            <div class="indikator-isi" style="width: 85%; background: linear-gradient(90deg, #22c55e, #4ade80);"></div>
        </div>
        <p style="font-size: 12px; color: #94a3b8; margin-top: 15px; font-weight: 600;">Unit toko aktif di shelter</p>
    </div>
</div>

<div class="panel-wawasan">
    <h3 class="judul-wawasan"><i class="fas fa-chart-line"></i> Wawasan Operasional</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        <p style="font-size: 15px; color: #cbd5e1; line-height: 1.8;">
            Sistem saat ini melaporkan <strong style="color: #4ade80;">stabilitas penuh</strong> pada seluruh modul. Tingkat okupansi komersial mencapai ambang batas optimal. Disarankan untuk memantau pendaftaran owner baru secara berkala.
        </p>
        <p style="font-size: 15px; color: #cbd5e1; line-height: 1.8;">
            Gunakan fungsionalitas <strong style="color: #38bdf8;">Kelola Pengguna</strong> untuk validasi entitas baru guna menjaga standar kualitas data Shelter Manahan.
        </p>
    </div>
</div>
@endsection