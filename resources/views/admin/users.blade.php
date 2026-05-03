@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .management-container { padding: 20px 0; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; }
    
    /* Header Style */
    .page-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 40px; 
        border-left: 8px solid #fcd34d; 
        padding-left: 20px; 
    }
    .page-header h1 { font-size: 32px; font-weight: 800; color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.3); margin: 0; }
    .page-header p { color: #fcd34d; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 12px; margin: 0; }

    /* Luxury Glass Card */
    .luxury-card { 
        background: rgba(255, 255, 255, 0.1); 
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 30px; 
        padding: 35px; 
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
        text-align: left;
    }
    
    .custom-table tbody tr { background: rgba(255, 255, 255, 0.05); transition: all 0.3s ease; }
    .custom-table tbody tr:hover { background: rgba(255, 255, 255, 0.08); transform: translateY(-3px); }
    .custom-table td { padding: 20px; vertical-align: middle; color: #ffffff; border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); }

    /* Modal Styling */
    .modal-overlay { 
        display: none; 
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        background: rgba(0,0,0,0.85); 
        backdrop-filter: blur(10px); 
        z-index: 9999; 
        align-items: center; 
        justify-content: center; 
    }
    
    /* Mencegah scroll pada body saat modal buka */
    body.modal-open { overflow: hidden; }

    .modal-content { 
        background: #1e293b; /* Warna solid agar tidak pusing */
        width: 90%; 
        max-width: 500px; 
        padding: 35px; 
        border-radius: 30px; 
        border: 1px solid rgba(255,255,255,0.1); 
        animation: modalIn 0.3s ease-out; 
    }

    @keyframes modalIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    
    .input-group { margin-bottom: 18px; }
    .input-group label { display: block; margin-bottom: 8px; font-weight: 700; color: #94a3b8; font-size: 11px; text-transform: uppercase; }
    
    .input-group input, .input-group select { 
        width: 100%; 
        padding: 14px; 
        border: 1.5px solid rgba(255,255,255,0.1); 
        background: rgba(15, 23, 42, 0.5); 
        color: white; 
        border-radius: 12px; 
        outline: none; 
    }

    /* FIX: Pewarnaan Dropdown Option */
    .input-group select option {
        background-color: #1e293b; /* Background dropdown gelap */
        color: white; /* Teks dropdown putih */
        padding: 10px;
    }

    .input-group input:focus, .input-group select:focus { border-color: #0284c7; }

    .btn-modal-save { background: #0284c7; color: white; padding: 12px; border-radius: 12px; border: none; font-weight: 800; cursor: pointer; transition: 0.3s; }
    .btn-modal-cancel { background: rgba(255,255,255,0.1); color: white; padding: 12px; border-radius: 12px; border: none; cursor: pointer; }

    /* Action Buttons */
    .btn-action { width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; transition: 0.3s; border: none; cursor: pointer; font-size: 14px; }
    .btn-edit { background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
    .btn-delete { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }

    @media (max-width: 768px) {
        /* ... Kode mobile kamu yang sebelumnya tetap ada ... */
        .custom-table thead { display: none; }
        .custom-table, .custom-table tbody, .custom-table tr, .custom-table td { display: block; width: 100%; }
        .custom-table tr { margin-bottom: 20px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1); padding: 15px; }
        .custom-table td { border: none; padding: 10px 5px; display: flex; justify-content: space-between; align-items: center; text-align: right; font-size: 14px; }
        .custom-table td::before { content: attr(data-label); font-weight: 800; color: #fcd34d; text-transform: uppercase; font-size: 10px; text-align: left; }
    }
</style>

<div class="management-container">
    <div class="container">
        <div class="page-header">
            <div>
                <h1>Manajemen Pengguna</h1>
                <p>Otoritas Pengelola Sistem Shelter Manahan</p>
            </div>
            <button class="btn-add" style="background: #0284c7; color: white; padding: 14px 25px; border-radius: 16px; font-weight: 800; border: none; cursor: pointer;" onclick="openModal('addModal')">
                <i class="fa-solid fa-user-plus"></i> TAMBAH ENTITAS
            </button>
        </div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.2); color: #4ade80; padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid rgba(74, 222, 128, 0.3); font-size: 13px;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="luxury-card">
            <div class="table-responsive">
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
                            <td data-label="No">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td data-label="Profil">
                                <span style="font-weight: 800;">{{ $user->name }}</span>
                            </td>
                            <td data-label="Email">{{ $user->email }}</td>
                            <td data-label="Role">
                                <span style="color: {{ $user->role == 'admin' ? '#fff' : '#fcd34d' }}; font-weight: 800;">{{ strtoupper($user->role) }}</span>
                            </td>
                            <td data-label="Terdaftar">{{ $user->created_at->format('d M Y') }}</td>
                            <td data-label="Aksi">
                                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                    <button class="btn-action btn-edit" onclick="openEditModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')">
                                        <i class="fa-solid fa-user-gear"></i>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="openDeleteModal('{{ $user->id }}', '{{ $user->name }}')">
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

<!-- Modal Add -->
<div id="addModal" class="modal-overlay">
    <div class="modal-content">
        <h3 style="color: #ffffff; margin-bottom: 25px;"><i class="fa-solid fa-user-plus" style="color: #fcd34d;"></i> Tambah User Baru</h3>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="input-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Nama Lengkap">
            </div>
            <div class="input-group">
                <label>Email Akses</label>
                <input type="email" name="email" required placeholder="user@mail.com">
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Min. 8 Karakter">
            </div>
            <div class="input-group">
                <label>Role Sistem</label>
                <select name="role" required>
                    <option value="" disabled selected>Pilih Otoritas</option>
                    <option value="owner">Pemilik Toko (Owner)</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="button" class="btn-modal-cancel" style="flex:1" onclick="closeModal('addModal')">BATAL</button>
                <button type="submit" class="btn-modal-save" style="flex:2">SIMPAN ENTITAS</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="modal-overlay">
    <div class="modal-content">
        <h3 style="color: #ffffff; margin-bottom: 25px;"><i class="fa-solid fa-user-pen" style="color: #38bdf8;"></i> Perbarui Otoritas</h3>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
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
                    <option value="owner">Pemilik Toko</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>
            <div class="input-group">
                <label>Password Baru (Opsional)</label>
                <input type="password" name="password">
            </div>
            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="button" class="btn-modal-cancel" style="flex:1" onclick="closeModal('editModal')">BATAL</button>
                <button type="submit" class="btn-modal-save" style="background: #38bdf8; flex:2">UPDATE DATA</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 420px; text-align: center;">
        <div style="font-size: 55px; color: #ef4444; margin-bottom: 20px;"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <h3 style="color: #ffffff; font-size: 22px;">Hapus Entitas?</h3>
        <p id="deleteText" style="color: #94a3b8; font-size: 14px; margin: 15px 0; line-height: 1.6;"></p>
        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <div style="display: flex; gap: 10px; margin-top: 25px;">
                <button type="button" class="btn-modal-cancel" style="flex:1" onclick="closeModal('deleteModal')">BATAL</button>
                <button type="submit" class="btn-modal-save" style="background: #ef4444; flex: 1;">HAPUS PERMANEN</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modals = ['addModal', 'editModal', 'deleteModal'];
        modals.forEach(id => {
            const modal = document.getElementById(id);
            if(modal) document.body.appendChild(modal);
        });
    });

    function openModal(id) { 
        document.getElementById(id).style.display = 'flex';
        document.body.classList.add('modal-open'); // Lock scroll
    }

    function closeModal(id) { 
        document.getElementById(id).style.display = 'none'; 
        document.body.classList.remove('modal-open'); // Unlock scroll
    }

    function openEditModal(id, name, email, role) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = role;
        document.getElementById('editForm').action = "/admin/users/" + id; 
        openModal('editModal');
    }

    function openDeleteModal(id, name) {
        document.getElementById('deleteText').innerText = "Hapus akses sistem untuk " + name + "?";
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