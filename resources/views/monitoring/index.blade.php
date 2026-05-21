@extends('layouts.app')

@section('title', 'Monitoring Kelas Kosong')

@section('content')
<div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: flex-end;">
    <div>
        <h1 class="dashboard-title">Monitoring Kelas Kosong</h1>
        <p class="dashboard-subtitle">Pantau kelas yang berpotensi kosong hari ini.</p>
    </div>
    <div>
        <div class="badge" style="padding: 8px 16px; font-size: 0.85rem; background-color: var(--surface-color); color: var(--accent-color); border: 1px solid var(--surface-border);">
            <i class="fa-regular fa-clock" style="margin-right: 6px;"></i> Hari ini: <strong>{{ $hariIni }}, {{ date('d F Y', strtotime($tanggalHariIni)) }}</strong>
        </div>
    </div>
</div>

<div class="panel">
    @if(count($kelasKosong) > 0)
        <table class="modern-table">
            <thead>
                <tr>
                    <th>JAM</th>
                    <th>KELAS</th>
                    <th>MATA PELAJARAN</th>
                    <th>GURU (IZIN)</th>
                    <th>KETERANGAN / TUGAS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelasKosong as $jadwal)
                @php
                    // Determine circle color for teacher initial
                    $colors = ['#fecaca', '#fef08a', '#f3e8ff', '#dcfce3', '#ffedd5', '#fce7f3']; // Use more reddish/warning tones if preferred, or standard
                    $textColors = ['#991b1b', '#854d0e', '#6b21a8', '#166534', '#9a3412', '#9d174d'];
                    $guruName = $jadwal->guru ? $jadwal->guru->nama : 'X';
                    $hash = crc32($guruName);
                    $colorIndex = abs($hash) % count($colors);
                    $bgColor = $colors[$colorIndex];
                    $textColor = $textColors[$colorIndex];
                    $initial = strtoupper(substr($guruName, 0, 1));
                @endphp
                <tr style="border-bottom: 1px solid #f3f4f6;">
                    <td style="padding: 16px 24px; color: #4b5563; font-size: 0.95rem;">
                        <span class="status-badge active" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">{{ $jadwal->jam }}</span>
                    </td>
                    <td style="padding: 16px 24px; font-weight: 700; color: #4b5563; font-size: 0.95rem;">
                        {{ $jadwal->kelas ? $jadwal->kelas->nama_kelas : '-' }}
                    </td>
                    <td style="padding: 16px 24px; font-weight: 700; color: #111827; font-size: 0.95rem;">
                        {{ $jadwal->mapel }}
                    </td>
                    <td style="padding: 16px 24px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 26px; height: 26px; background: {{ $bgColor }}; color: {{ $textColor }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">
                                {{ $initial }}
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-size: 0.95rem; color: #4b5563;">{{ $guruName !== 'X' ? $guruName : '-' }}</span>
                                <span style="font-size: 0.75rem; color: #ef4444; font-weight: 600;">(Sedang Izin)</span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 16px 24px; color: #6b7280; font-style: italic; font-size: 0.9rem;">
                        "{{ $jadwal->keterangan ?? 'Tidak ada keterangan/tugas.' }}"
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; color: var(--text-secondary); padding: 48px 16px;">
            <i class="fa-regular fa-face-smile-beam" style="font-size: 3rem; margin-bottom: 16px; color: var(--success); opacity: 0.7;"></i>
            <h3 style="font-size: 1.1rem; color: var(--text-primary);">Semua kelas terisi hari ini!</h3>
            <p style="font-size: 0.9rem; margin-top: 8px;">Tidak ada laporan guru izin atau berhalangan mengajar.</p>
        </div>
    @endif
</div>
@endsection
