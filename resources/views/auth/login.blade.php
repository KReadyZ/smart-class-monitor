<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Class Monitor</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: var(--bg-primary);
        }
        
        /* Hide native Edge/IE password reveal button */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }
        .auth-container {
            width: 100%;
            max-width: 400px;
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
            margin-bottom: 1.5rem;
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
            <p>Silakan masuk ke akun Anda</p>
        </div>

        <div class="auth-card">
            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <div><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control" required placeholder="Masukkan password Anda" style="padding-right: 40px;">
                        <i class="fa-regular fa-eye" id="togglePasswordIcon" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-secondary);" onclick="togglePassword('password', 'togglePasswordIcon')"></i>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Masuk</button>
            </form>

            <div class="auth-links">
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 5px;">Belum punya akun?</p>
                <a href="{{ route('register') }}">Daftar Sekarang</a>
            </div>
        </div>
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
</body>
</html>
