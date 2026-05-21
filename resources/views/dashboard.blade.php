@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title">Selamat Pagi, Administrator</h1>
    <p class="dashboard-subtitle">Berikut adalah ringkasan aktivitas akademik hari ini.</p>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon-wrapper blue">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-info">
            <span class="stat-label">TOTAL GURU</span>
            <span class="stat-value">{{ $totalGuru }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrapper green">
            <i class="fa-solid fa-door-open"></i>
        </div>
        <div class="stat-info">
            <span class="stat-label">TOTAL KELAS</span>
            <span class="stat-value">{{ $totalKelas }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrapper yellow">
            <i class="fa-solid fa-user-xmark"></i>
        </div>
        <div class="stat-info">
            <span class="stat-label">GURU IZIN HARI INI</span>
            <span class="stat-value">{{ $guruIzinHariIni }}</span>
        </div>
    </div>
</div>

<div class="dashboard-layout">
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title">Class Monitoring</h2>
            <a href="{{ route('monitoring.index') }}" class="panel-action">Lihat Semua</a>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>RUANG KELAS</th>
                    <th>MATA PELAJARAN</th>
                    <th>PENGAJAR</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwals as $jadwal)
                <tr>
                    <td>
                        @php
                            $namaKelas = $jadwal->kelas->nama_kelas ?? null;
                            $guruTerjadwal = $jadwal->guru;
                            $guruDinamis = null;
                            $statusGuruHariIni = null;

                            // Jika guru sudah diinput oleh admin
                            if ($guruTerjadwal) {
                                $statusGuruHariIni = $guruTerjadwal->statusGurus ? $guruTerjadwal->statusGurus->where('tanggal', date('Y-m-d'))->first() : null;
                            } 
                            // Jika belum ada guru, cari secara dinamis dari guru yang input kehadiran di ruang kelas ini
                            elseif ($namaKelas) {
                                $statusMatch = collect($semuaStatusHariIni)->first(function($status) use ($namaKelas) {
                                    return strtolower(trim($status->ruang_kelas)) === strtolower(trim($namaKelas)) && $status->status == 'hadir';
                                });
                                if ($statusMatch) {
                                    $guruDinamis = $statusMatch->guru;
                                    $statusGuruHariIni = $statusMatch;
                                }
                            }

                            // Tentukan guru final yang akan ditampilkan (prioritas dinamis jika ada, jika tidak pakai yang terjadwal)
                            $guruFinal = $guruDinamis ?? $guruTerjadwal;
                            $namaGuruTampil = $guruFinal ? $guruFinal->nama : '-';

                            $ruangKelasInput = $statusGuruHariIni ? $statusGuruHariIni->ruang_kelas : null;
                            $guruIzin = $statusGuruHariIni && $statusGuruHariIni->status == 'izin';

                            // Hitung status real-time
                            $hari_map = [
                                'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 0
                            ];
                            $currentDay = \Carbon\Carbon::now()->dayOfWeek;
                            $jadwalDay = $hari_map[$jadwal->hari] ?? -1;
                            $currentTime = \Carbon\Carbon::now()->format('H:i');
                            
                            $parts = explode('-', $jadwal->jam);
                            $start_time = str_replace('.', ':', trim($parts[0] ?? '00:00'));
                            $end_time = str_replace('.', ':', trim($parts[1] ?? '23:59'));

                            $statusJadwal = 'Menunggu';
                            if ($currentDay == $jadwalDay) {
                                if ($currentTime < $start_time) {
                                    $statusJadwal = 'Menunggu';
                                } elseif ($currentTime >= $start_time && $currentTime <= $end_time) {
                                    $statusJadwal = 'Berlangsung';
                                } else {
                                    $statusJadwal = 'Selesai';
                                }
                            } elseif ($currentDay > 0 && $currentDay > $jadwalDay) {
                                $statusJadwal = 'Selesai';
                            } elseif ($currentDay == 0 && $jadwalDay > 0) {
                                $statusJadwal = 'Selesai';
                            } else {
                                $statusJadwal = 'Menunggu'; // Untuk hari yang akan datang
                            }
                        @endphp
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $jadwal->kelas->nama_kelas ?? 'Kelas' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 2px;">
                            {{ $ruangKelasInput ? 'Ruang: ' . $ruangKelasInput : '-' }}
                        </div>
                    </td>
                    <td style="color: var(--text-secondary); font-size: 0.9rem;">{{ $jadwal->mapel }}<br><span style="font-size:0.75rem; color:#9ca3af;">{{ $jadwal->hari }}, {{ $jadwal->jam }}</span></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div class="user-avatar" style="width: 24px; height: 24px; font-size: 0.8rem; background: #e2e8f0; color: var(--text-secondary); display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <span style="font-size: 0.85rem; color: var(--text-primary); font-weight: 500;">
                                {{ $namaGuruTampil }}
                                @if($guruDinamis)
                                    <i class="fa-solid fa-bolt" style="color: #f59e0b; font-size: 0.75rem; margin-left: 4px;" title="Terisi Otomatis dari Kehadiran"></i>
                                @endif
                            </span>
                        </div>
                    </td>
                    <td>
                        @if($guruIzin && $currentDay == $jadwalDay)
                            <span class="status-badge danger">Kosong (Izin)</span>
                        @elseif($statusJadwal == 'Berlangsung')
                            <span class="status-badge" style="background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe;">Berlangsung</span>
                        @elseif($statusJadwal == 'Selesai')
                            <span class="status-badge" style="background:#f3f4f6; color:#6b7280; border:1px solid #e5e7eb;">Selesai</span>
                        @else
                            <span class="status-badge" style="background:#fef3c7; color:#d97706; border:1px solid #fde68a;">Menunggu</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                @if($jadwals->isEmpty())
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--text-secondary); padding: 32px;">Belum ada jadwal hari ini.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title">
                Pembaruan Guru
                <span class="badge">Baru</span>
            </h2>
        </div>
        <div class="activity-list">
            @foreach($pembaruanGuru as $status)
            <div class="activity-item">
                <div class="activity-icon {{ $status->status == 'izin' ? 'warning' : 'success' }}">
                    <i class="fa-solid {{ $status->status == 'izin' ? 'fa-triangle-exclamation' : 'fa-circle-check' }}"></i>
                </div>
                <div class="activity-details">
                    <div class="activity-title">{{ $status->status == 'izin' ? 'Izin Sakit / Berhalangan' : 'Laporan Selesai' }}</div>
                    <div class="activity-desc">
                        {{ $status->guru->nama ?? 'Guru' }} {{ $status->status == 'izin' ? 'tidak dapat hadir hari ini.' : 'telah mengonfirmasi kehadiran.' }}
                        @if($status->ruang_kelas)
                            <br><span style="font-size: 0.8rem; color: var(--primary); font-weight: 500;">Di Ruang/Lab: {{ $status->ruang_kelas }}</span>
                        @endif
                        @if($status->keterangan)
                            <br><span style="font-style: italic; color: var(--text-secondary);">"{{ \Illuminate\Support\Str::limit($status->keterangan, 50) }}"</span>
                        @endif
                    </div>
                    <div class="activity-time">{{ $status->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @endforeach
            @if($pembaruanGuru->isEmpty())
            <div style="text-align: center; color: var(--text-secondary); padding: 32px 16px;">
                <i class="fa-regular fa-clock" style="font-size: 2rem; margin-bottom: 12px; opacity: 0.5;"></i>
                <p style="font-size: 0.85rem;">Belum ada pembaruan hari ini.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
