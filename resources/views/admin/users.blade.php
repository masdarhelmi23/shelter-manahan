@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .management-container { padding: 20px 0; min-height: 100vh; font-family: 'Inter', sans-serif; }
    
    /* Header Style - Senada dengan Dashboard */
    .page-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 40px; 
        border-left: 8px solid #fcd34d; 
        padding-left: 20px; 
    }
    .page-header h1 { font-size: 36px; font-weight: 800; color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.3); margin: 0; }
    .page-header p { color: #fcd34d; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 13px; margin: 0; }

    /* Luxury Glass Card */
    .luxury-card { 
        background: rgba(255, 255, 255, 0.1); 
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 30px; 
        padding: 40px; 
        border: 1px solid rgba(255, 255, 255, 0.2); 
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    }
    
    /* Table Styling */
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0 15px; }
    .custom-table thead th { 
        color: #fcd34d; 
        text-transform: uppercase; 
        font-size: 11px; 
        letter-spacing: 2px; 
        padding: 15px; 
        font-weight: 800;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .custom-table tbody tr { background: rgba(255, 255, 255, 0.05); transition: all 0.3s ease; }
    .custom-table tbody tr:hover { background: rgba(255, 255, 255, 0.1); transform: scale(1.01); }
    
    .custom-table td { padding: 20px; vertical-align: middle; color: #ffffff; border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); }

    /* Badge & Buttons */
    .badge-role { 
        padding: 6px 14px; 
        border-radius: 10px; 
        font-size: 10px; 
        font-weight: 800; 
        color: white; 
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
        text-transform: uppercase;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .btn-add { 
        background: #0284c7; 
        color: white; 
        padding: 14px 25px; 
        border-radius: 16px; 
        font-weight: 800; 
        cursor: pointer; 
        border: none; 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(2, 132, 199, 0.3);
    }
    .btn-add:hover { background: #0ea5e9; transform: translateY(-3px); box-shadow: 0 15px 30px rgba(2, 132, 199, 0.5); }
    
    .btn-action { width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.3s; border: none; cursor: pointer; font-size: 14px; }
    .btn-edit { background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
    .btn-edit:hover { background: #0284c7; color: white; }
    .btn-delete { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
    .btn-delete:hover { background: #ef4444; color: white; }

    /* Modal Glassmorphism Styling */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(10px); z-index: 2000; align-items: center; justify-content: center; }
    .modal-content { 
        background: rgba(30, 41, 59, 0.95); 
        width: 90%; 
        max-width: 500px; 
        padding: 40px; 
        border-radius: 30px; 
        border: 1px solid rgba(255,255,255,0.1);
        animation: slideDown 0.3s ease-out; 
    }
    @keyframes slideDown { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    
    .input-group { margin-bottom: 20px; }
    .input-group label { display: block; margin-bottom: 8px; font-weight: 700; color: #fcd34d; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
    .input-group input, .input-group select { 
        width: 100%; 
        padding: 14px 18px; 
        border: 2px solid rgba(255,255,255,0.1); 
        background: rgba(255,255,255,0.05);
        color: white;
        border-radius: 12px; 
        outline: none; 
        transition: 0.3s; 
    }
    .input-group input:focus { border-color: #0284c7; background: rgba(255,255,255,0.1); }
    .input-group select option { background: #1e293b; color: white; }

    .btn-modal-save { background: #0284c7; color: white; padding: 12px; border-radius: 12px; border: none; font-weight: 800; cursor: pointer; flex: 2; transition: 0.3s; }
    .btn-modal-cancel { background: rgba(255,255,255,0.1); color: white; padding: 12px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); flex: 1; cursor: pointer; }

    /* ================= MOBILE ONLY ================= */
    @media (max-width: 768px){

        .management-container{
            padding: 10px 0;
        }

        .page-header{
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 25px;
            padding-left: 14px;
        }

        .page-header h1{
            font-size: 24px;
            line-height: 1.2;
        }

        .page-header p{
            font-size: 11px;
            letter-spacing: .5px;
        }

        .btn-add{
            width: 100%;
            justify-content: center;
            padding: 13px 18px;
            font-size: 13px;
            border-radius: 14px;
        }

        .luxury-card{
            padding: 15px;
            border-radius: 20px;
        }

        .custom-table{
            min-width: 760px;
        }

        .custom-table thead th{
            font-size: 10px;
            padding: 12px;
        }

        .custom-table td{
            padding: 14px 12px;
            font-size: 13px;
            white-space: nowrap;
        }

        .badge-role{
            font-size: 9px;
            padding: 6px 10px;
        }

        .btn-action{
            width: 34px;
            height: 34px;
            font-size: 12px;
            border-radius: 10px;
        }

        .modal-content{
            width: 95%;
            padding: 22px;
            border-radius: 22px;
            max-height: 95vh;
            overflow-y: auto;
        }

        .input-group input,
        .input-group select{
            padding: 12px 14px;
            font-size: 14px;
        }

        .btn-modal-save,
        .btn-modal-cancel{
            padding: 12px;
            font-size: 13px;
        }

        .modal-content form div[style*="display: flex"]{
            flex-direction: column;
        }
    }

    @media (max-width: 480px){

        .page-header h1{
            font-size: 21px;
        }

        .page-header p{
            font-size: 10px;
        }

        .luxury-card{
            padding: 12px;
        }

        .modal-content{
            padding: 18px;
        }
    }
</style>


<div class="management-container">
    <div class="container">
        <div class="page-header">
            <div>
                <h1>Manajemen Pengguna</h1>
                <p>Otoritas Pengelola Sistem Shelter Manahan</p>
            </div>
            <button class="btn-add" onclick="openModal('addModal')">
                <i class="fa-solid fa-user-plus"></i> TAMBAH ENTITAS
            </button>
        </div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.2); color: #4ade80; padding: 20px; border-radius: 20px; margin-bottom: 30px; border: 1px solid rgba(74, 222, 128, 0.3);">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="luxury-card">
            <div style="overflow-x: auto;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Profil Pengguna</th>
                            <th>Email Akses</th>
                            <th>Level Role</th>
                            <th>Registrasi</th>
                            <th style="text-align: center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td style="color: #94a3b8; font-weight: 800;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="width: 45px; height: 45px; background: rgba(2, 132, 199, 0.2); color: #38bdf8; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 900; border: 1px solid rgba(56, 189, 248, 0.3);">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span style="font-weight: 800; font-size: 16px;">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td style="font-weight: 600; color: #cbd5e1;">{{ $user->email }}</td>
                            <td>
                                <span class="badge-role" style="background: {{ $user->role == 'admin' ? 'rgba(15, 23, 42, 0.5)' : ($user->role == 'owner' ? 'rgba(245, 158, 11, 0.2)' : 'rgba(34, 197, 94, 0.2)') }}; color: {{ $user->role == 'admin' ? '#ffffff' : ($user->role == 'owner' ? '#fbbf24' : '#4ade80') }};">
                                    <i class="fa-solid fa-shield-halved"></i> {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td style="color: #94a3b8; font-size: 13px; font-weight: 600;">{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 10px; justify-content: center;">
                                    <button class="btn-action btn-edit" 
                                            onclick="openEditModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')" 
                                            title="Edit Profil">
                                        <i class="fa-solid fa-user-gear"></i>
                                    </button>
                                    
                                    <button class="btn-action btn-delete" onclick="openDeleteModal('{{ $user->id }}', '{{ $user->name }}')" title="Hapus Entitas">
                                        <i class="fa-solid fa-user-minus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="addModal" class="modal-overlay">
    <div class="modal-content">
        <h3 style="margin-top: 0; color: #ffffff; margin-bottom: 25px;"><i class="fa-solid fa-user-plus" style="color: #fcd34d;"></i> Registrasi User Baru</h3>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="input-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Input nama sesuai identitas">
            </div>
            <div class="input-group">
                <label>Email Akses</label>
                <input type="email" name="email" required placeholder="user@sheltermanahan.com">
            </div>
            <div class="input-group">
                <label>Otoritas Password</label>
                <input type="password" name="password" required placeholder="Konfigurasi password baru">
            </div>
            <div class="input-group">
                <label>Level Akses Sistem</label>
                <select name="role" required>
                    <option value="" disabled selected>Pilih Role</option>
                    <option value="owner">Pemilik Toko (Owner)</option>
                    <option value="admin">Administrator Sistem</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('addModal')">BATAL</button>
                <button type="submit" class="btn-modal-save">SIMPAN DATA</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal-overlay">
    <div class="modal-content">
        <h3 style="margin-top: 0; color: #ffffff; margin-bottom: 25px;"><i class="fa-solid fa-user-pen" style="color: #38bdf8;"></i> Perbarui Otoritas</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="input-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" id="edit_name" required>
            </div>
            <div class="input-group">
                <label>Email Akses</label>
                <input type="email" name="email" id="edit_email" required>
            </div>
            <div class="input-group">
                <label>Level Role</label>
                <select name="role" id="edit_role" required>
                    <option value="owner">Pemilik Toko (Owner)</option>
                    <option value="admin">Administrator Sistem</option>
                </select>
            </div>
            <p style="font-size: 11px; color: #f87171; margin-bottom: 10px; font-weight: 700;">*KOSONGKAN JIKA TIDAK INGIN MENGUBAH PASSWORD</p>
            <div class="input-group">
                <label>Password Baru</label>
                <input type="password" name="password">
            </div>
            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('editModal')">BATAL</button>
                <button type="submit" class="btn-modal-save" style="background: #38bdf8;">UPDATE DATA</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 450px; text-align: center;">
        <div style="font-size: 60px; color: #ef4444; margin-bottom: 20px;"><i class="fa-solid fa-shield-circle-exclamation"></i></div>
        <h3 style="margin: 0; color: #ffffff; font-size: 24px;">Konfirmasi Penghapusan</h3>
        <p id="deleteText" style="color: #94a3b8; margin-top: 15px; line-height: 1.6;"></p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('deleteModal')">BATALKAN</button>
                <button type="submit" class="btn-modal-save" style="background: #ef4444; flex: 1;">HAPUS PERMANEN</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    function openEditModal(id, name, email, role) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        const roleSelect = document.getElementById('edit_role');
        roleSelect.value = role;
        document.getElementById('editForm').action = "/admin/users/" + id; 
        openModal('editModal');
    }

    function openDeleteModal(id, name) {
        document.getElementById('deleteText').innerText = "Apakah Anda yakin ingin menghapus akses sistem untuk entitas: " + name + "? Tindakan ini tidak dapat dibatalkan.";
        document.getElementById('deleteForm').action = "/admin/users/" + id;
        openModal('deleteModal');
    }

    window.onclick = function(event) {
        if (event.target.className === 'modal-overlay') {
            const modals = ['addModal', 'editModal', 'deleteModal'];
            modals.forEach(id => closeModal(id));
        }
    }
</script>
@endsection