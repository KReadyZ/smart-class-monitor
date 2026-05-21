@extends('layouts.app')

@section('title', 'Pesan dan Tugas')

@section('content')
<div class="dashboard-header" style="margin-bottom: 2rem;">
    <h1 class="dashboard-title">Halo, {{ auth()->user()->name }}</h1>
    <p class="dashboard-subtitle">Berikut adalah pesan dan informasi tugas dari LMS.</p>
</div>

<div class="panel">
    <div class="panel-header">
        <h2 class="panel-title">Informasi Pesan & Tugas LMS ({{ $hariIni }}, {{ date('d F Y', strtotime($tanggalHariIni)) }})</h2>
    </div>
    
    <div class="activity-list" style="padding: 20px;">
        @forelse($jadwals as $jadwal)
        <div class="activity-item" style="background: var(--bg-primary); padding: 16px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 15px;">
            @if($jadwal->status_kehadiran === 'izin')
                <div class="activity-icon blue" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-bell"></i>
                </div>
            @else
                <div class="activity-icon green" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-book"></i>
                </div>
            @endif
            
            <div class="activity-details">
                <div class="activity-title" style="font-size: 1.1rem;">{{ $jadwal->mapel }} ({{ $jadwal->kelas ? $jadwal->kelas->nama_kelas : '-' }})</div>
                <div class="activity-desc" style="margin-top: 8px; color: var(--text-primary);">
                    Guru: {{ $jadwal->guru ? $jadwal->guru->nama : '-' }}<br>
                    Status Guru: 
                    @if($jadwal->status_kehadiran === 'izin')
                        <span class="status-badge warning" style="display: inline-block; padding: 2px 8px; margin: 4px 0;">Izin / Tidak Hadir</span>
                    @else
                        <span class="status-badge success" style="display: inline-block; padding: 2px 8px; margin: 4px 0;">Hadir (Mengajar)</span>
                    @endif
                </div>
                
                {{-- Logic: if teacher is on leave (izin), show the message box; if not izin (hadir), keep it empty/hidden --}}
                @if($jadwal->status_kehadiran === 'izin')
                    <div class="activity-desc" style="margin-top: 10px; padding: 12px; background: rgba(59, 130, 246, 0.05); border-left: 3px solid var(--accent-blue); border-radius: 4px;">
                        "{{ $jadwal->keterangan ?? 'Tidak ada keterangan/tugas.' }}"
                    </div>
                @endif
                
                <div class="activity-time" style="margin-top: 10px;">{{ $hariIni }}, Jam Ke-{{ $jadwal->jam }}</div>
            </div>
        </div>
        @empty
        <div style="text-align: center; color: var(--text-secondary); padding: 40px 16px;">
            <i class="fa-regular fa-calendar-times" style="font-size: 3rem; margin-bottom: 16px; color: var(--text-secondary); opacity: 0.5;"></i>
            <h3 style="font-size: 1.1rem; color: var(--text-primary);">Tidak ada jadwal pelajaran hari ini!</h3>
            <p style="font-size: 0.9rem; margin-top: 8px;">Semua pelajaran hari ini selesai atau tidak ada jadwal.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
