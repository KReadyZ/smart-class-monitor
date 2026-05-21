<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smart Class Monitor</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-color: var(--bg-primary);
            padding: 20px 0;
        }
        
        /* Hide native Edge/IE password reveal button */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }
        .auth-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-logo img {
            height: 60px;
            margin-bottom: 1rem;
        }
        .auth-logo h1 {
            font-size: 1.5rem;
            color: var(--text-primary);
            margin: 0;
        }
        .auth-logo p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 5px;
        }
        .auth-card {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.9rem;
        }
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: inherit;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
            padding-right: 2.5rem;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 10px;
            transition: background-color 0.2s ease;
        }
        .btn-submit:hover {
            background: var(--accent-hover);
        }
        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
        }
        .auth-links a {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .auth-links a:hover {
            text-decoration: underline;
        }
        .alert-error {
            background-color: #fee2e2;
            color: #ef4444;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            border: 1px solid #fca5a5;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" onerror="this.src='https://via.placeholder.com/60'">
            <h1>Smart Class Monitor</h1>
            <p>Daftar akun baru</p>
        </div>

        <div class="auth-card">
            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <div><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus placeholder="Contoh: Budi Santoso, S.Pd">
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="Contoh: budi@sekolah.id">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="role">Role / Peran</label>
                    <select id="role" name="role" class="form-control" required onchange="toggleSecretCode()">
                        <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin / Guru Piket</option>
                    </select>
                </div>

                <div class="form-group" id="secret_code_group" style="display: none;">
                    <label class="form-label" for="secret_code">Kode Akses</label>
                    <div style="position: relative;">
                        <input type="password" id="secret_code" name="secret_code" class="form-control" placeholder="Masukkan kode akses dari sekolah" style="padding-right: 40px;">
                        <i class="fa-regular fa-eye" id="toggleSecretCodeIcon" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-secondary);" onclick="togglePassword('secret_code', 'toggleSecretCodeIcon')"></i>
                    </div>
                    <small style="color: var(--text-secondary); font-size: 0.8rem; margin-top: 4px; display: block;">*Wajib diisi untuk pendaftaran Guru & Admin</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="foto_profil">Foto Profil (Opsional)</label>
                    <div style="display: flex; gap: 10px; align-items: flex-start;">
                        <div style="flex-grow: 1;">
                            <input type="file" id="foto_profil" name="foto_profil" class="form-control" accept="image/*" style="padding: 8px;">
                            <small style="color: var(--text-secondary); font-size: 0.8rem; margin-top: 4px; display: block;">*Format gambar, maks 2MB</small>
                        </div>
                        <button type="button" onclick="document.getElementById('foto_profil').value = '';" style="background: transparent; color: #ef4444; border: 1px solid #ef4444; padding: 8px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s; white-space: nowrap;" onmouseover="this.style.background='#ef4444'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#ef4444';" title="Batalkan pilihan foto">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control" required placeholder="Minimal 8 karakter" style="padding-right: 40px;">
                        <i class="fa-regular fa-eye" id="togglePasswordIcon" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-secondary);" onclick="togglePassword('password', 'togglePasswordIcon')"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                    <div style="position: relative;">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Ulangi password di atas" style="padding-right: 40px;">
                        <i class="fa-regular fa-eye" id="toggleConfirmIcon" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-secondary);" onclick="togglePassword('password_confirmation', 'toggleConfirmIcon')"></i>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Daftar</button>
            </form>

            <div class="auth-links">
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 5px;">Sudah punya akun?</p>
                <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>

    <script>
        function toggleSecretCode() {
            const role = document.getElementById('role').value;
            const secretGroup = document.getElementById('secret_code_group');
            const secretInput = document.getElementById('secret_code');
            
            if (role === 'admin' || role === 'guru') {
                secretGroup.style.display = 'block';
                secretInput.required = true;
            } else {
                secretGroup.style.display = 'none';
                secretInput.required = false;
                secretInput.value = '';
            }
        }
        
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
        
        // Panggil saat halaman pertama dimuat (berguna jika ada old('role'))
        document.addEventListener('DOMContentLoaded', toggleSecretCode);
    </script>
</body>
</html>
