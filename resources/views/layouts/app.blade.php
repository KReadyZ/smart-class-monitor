<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Smart Class Monitor</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .logout-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1.2rem;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logout-btn:hover {
            color: #ef4444;
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: var(--bg-secondary);
            min-width: 250px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            border-radius: 8px;
            z-index: 1;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        .dropdown-content.show {
            display: block;
            animation: fadeIn 0.2s ease-in-out;
        }
        .dropdown-item {
            color: var(--text-primary);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }
        .dropdown-item:last-child {
            border-bottom: none;
        }
        .dropdown-item:hover {
            background-color: var(--bg-primary);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Hide native Edge/IE password reveal button */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="SKENSA Logo" onerror="this.src='https://via.placeholder.com/40'">
            <div class="logo-text-wrapper">
                <span class="logo-text">Smart Class</span>
                <span class="logo-text">Monitor</span>
                <span class="logo-subtext">SMK NEGERI 1 DENPASAR</span>
            </div>
        </div>

        <ul class="nav-links">
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'guru_piket')
            <li>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-border-all"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('jadwal.index') }}" class="nav-link {{ request()->routeIs('jadwal.index') ? 'active' : '' }}">
                    <i class="fa-regular fa-calendar"></i>
                    <span>Jadwal</span>
                </a>
            </li>
            <li>
                <a href="{{ route('monitoring.index') }}" class="nav-link {{ request()->routeIs('monitoring.index') ? 'active' : '' }}">
                    <i class="fa-regular fa-eye"></i>
                    <span>Monitoring</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role === 'guru')
            <li>
                <a href="{{ route('kehadiran.create') }}" class="nav-link {{ request()->routeIs('kehadiran.create') ? 'active' : '' }}">
                    <i class="fa-regular fa-user"></i>
                    <span>Kehadiran Guru</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role === 'siswa')
            <li>
                <a href="{{ route('siswa.pesan') }}" class="nav-link {{ request()->routeIs('siswa.pesan') ? 'active' : '' }}">
                    <i class="fa-regular fa-envelope"></i>
                    <span>Kotak Pesan</span>
                </a>
            </li>
            @endif
        </ul>

        <div class="user-profile">
            <div class="user-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; background: var(--border-color);">
                @if(auth()->user()->foto_profil)
                    <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    <i class="fa-solid fa-user"></i>
                @endif
            </div>
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <span class="user-role" style="text-transform: capitalize;">{{ auth()->user()->role }}</span>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <form action="{{ route('search') }}" method="GET" class="search-bar" style="margin: 0;">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-secondary);"></i>
                <input type="text" name="q" placeholder="Cari data, kelas, atau guru..." value="{{ request('q') }}" required style="background: transparent; border: none; outline: none; width: 100%; color: var(--text-primary);">
                <button type="submit" style="display: none;"></button>
            </form>

            <div class="header-actions">
                <!-- Notification Dropdown -->
                @php
                    $notifications = \App\Models\StatusGuru::with('guru')->where('tanggal', now()->toDateString())->where('status', 'izin')->get();
                @endphp
                <div class="dropdown">
                    <div style="position: relative; cursor: pointer;" onclick="toggleDropdown('notifDropdown')">
                        <i class="fa-regular fa-bell header-icon"></i>
                        @if($notifications->count() > 0)
                            <span style="position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; border-radius: 50%; width: 16px; height: 16px; font-size: 0.7rem; display: flex; align-items: center; justify-content: center;">{{ $notifications->count() }}</span>
                        @endif
                    </div>
                    <div id="notifDropdown" class="dropdown-content">
                        <div style="padding: 12px 16px; border-bottom: 1px solid var(--border-color); font-weight: bold; background: var(--bg-primary);">Notifikasi Hari Ini</div>
                        @if($notifications->count() > 0)
                            @foreach($notifications as $notif)
                                <a href="#" class="dropdown-item">
                                    <div style="font-weight: 500; color: #ef4444;"><i class="fa-solid fa-circle-exclamation" style="margin-right: 5px;"></i> Guru Izin</div>
                                    <div style="font-size: 0.85rem; margin-top: 4px;">{{ $notif->guru->nama ?? 'Guru' }} izin hari ini.</div>
                                </a>
                            @endforeach
                        @else
                            <div class="dropdown-item" style="color: var(--text-secondary); text-align: center;">Tidak ada notifikasi baru</div>
                        @endif
                    </div>
                </div>

                <!-- Settings Link -->
                <a href="{{ route('pengaturan') }}" style="color: inherit; text-decoration: none;">
                    <i class="fa-solid fa-gear header-icon"></i>
                </a>

                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn header-icon" title="Logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </header>

        <div class="content-area">
            @yield('content')
        </div>
        
    </main>

    <script>
        function toggleDropdown(id) {
            document.getElementById(id).classList.toggle("show");
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.fa-bell') && !event.target.closest('.dropdown')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
