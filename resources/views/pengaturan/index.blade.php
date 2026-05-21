@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="content-header">
    <h2 class="content-title">Pengaturan Akun</h2>
</div>

<div class="card" style="max-width: 600px;">
    @if(session('success'))
        <div style="background: rgba(34, 197, 94, 0.1); color: #15803d; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(34, 197, 94, 0.2);">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group" style="margin-bottom: 25px; display: flex; align-items: center; gap: 20px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; background: var(--border-color); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                @if(auth()->user()->foto_profil)
                    <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i class="fa-solid fa-user" style="font-size: 2rem; color: var(--text-secondary);"></i>
                @endif
            </div>
            <div style="flex-grow: 1;">
                <label class="form-label" style="display: block; margin-bottom: 5px; font-weight: 500; color: var(--text-primary);">Ubah Foto Profil</label>
                <div style="display: flex; gap: 10px; align-items: flex-start;">
                    <div style="flex-grow: 1;">
                        <input type="file" name="foto_profil" class="form-control" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; background: var(--bg-secondary); color: var(--text-primary);">
                        <small style="color: var(--text-secondary); font-size: 0.8rem; margin-top: 4px; display: block;">*Format gambar (JPG/PNG), maks 2MB</small>
                        @error('foto_profil') <small style="color: #ef4444;">{{ $message }}</small> @enderror
                    </div>
                    @if(auth()->user()->foto_profil)
                        <button type="button" onclick="document.getElementById('formHapusFoto').submit();" style="background: transparent; color: #ef4444; border: 1px solid #ef4444; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: 500; transition: all 0.3s; white-space: nowrap;" onmouseover="this.style.background='#ef4444'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#ef4444';">
                            <i class="fa-solid fa-trash" style="margin-right: 5px;"></i> Hapus
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label class="form-label" style="display: block; margin-bottom: 5px; font-weight: 500; color: var(--text-primary);">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; background: var(--bg-secondary); color: var(--text-primary);" required>
            @error('name') <small style="color: #ef4444;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label class="form-label" style="display: block; margin-bottom: 5px; font-weight: 500; color: var(--text-primary);">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; background: var(--bg-secondary); color: var(--text-primary);" required>
            @error('email') <small style="color: #ef4444;">{{ $message }}</small> @enderror
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 25px 0;">
        <h3 style="font-size: 1.1rem; color: var(--text-primary); margin-bottom: 15px;">Ubah Password <span style="font-size: 0.85rem; color: var(--text-secondary); font-weight: normal;">(Biarkan kosong jika tidak ingin mengubah)</span></h3>

        <div class="form-group" style="margin-bottom: 15px;">
            <label class="form-label" style="display: block; margin-bottom: 5px; font-weight: 500; color: var(--text-primary);">Password Saat Ini</label>
            <div style="position: relative;">
                <input type="password" id="current_password" name="current_password" class="form-control" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; background: var(--bg-secondary); color: var(--text-primary); padding-right: 40px;">
                <i class="fa-regular fa-eye" id="toggleCurrentPasswordIcon" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-secondary);" onclick="togglePassword('current_password', 'toggleCurrentPasswordIcon')"></i>
            </div>
            @error('current_password') <small style="color: #ef4444;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label class="form-label" style="display: block; margin-bottom: 5px; font-weight: 500; color: var(--text-primary);">Password Baru</label>
            <div style="position: relative;">
                <input type="password" id="password" name="password" class="form-control" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; background: var(--bg-secondary); color: var(--text-primary); padding-right: 40px;">
                <i class="fa-regular fa-eye" id="togglePasswordIcon" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-secondary);" onclick="togglePassword('password', 'togglePasswordIcon')"></i>
            </div>
            @error('password') <small style="color: #ef4444;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <label class="form-label" style="display: block; margin-bottom: 5px; font-weight: 500; color: var(--text-primary);">Konfirmasi Password Baru</label>
            <div style="position: relative;">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; background: var(--bg-secondary); color: var(--text-primary); padding-right: 40px;">
                <i class="fa-regular fa-eye" id="toggleConfirmIcon" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-secondary);" onclick="togglePassword('password_confirmation', 'toggleConfirmIcon')"></i>
            </div>
        </div>

        <button type="submit" style="background: var(--accent-color); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 500; transition: background 0.3s;">
            Simpan Perubahan
        </button>
    </form>

    <form id="formHapusFoto" action="{{ route('pengaturan.hapus_foto') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
