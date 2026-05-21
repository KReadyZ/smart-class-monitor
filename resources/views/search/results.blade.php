@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
<div class="content-header">
    <h2 class="content-title">Hasil Pencarian untuk: "{{ $query }}"</h2>
</div>

<div class="card" style="margin-bottom: 20px;">
    <h3 style="margin-bottom: 15px; font-size: 1.1rem; color: var(--text-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Data Guru ({{ $gurus->count() }})</h3>
    @if($gurus->isEmpty())
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Tidak ada guru yang ditemukan.</p>
    @else
        <ul style="list-style: none; padding: 0;">
            @foreach($gurus as $guru)
                <li style="padding: 10px 0; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-user-tie" style="color: var(--accent-color);"></i>
                    <span>{{ $guru->nama }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>

<div class="card" style="margin-bottom: 20px;">
    <h3 style="margin-bottom: 15px; font-size: 1.1rem; color: var(--text-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Data Kelas ({{ $kelas->count() }})</h3>
    @if($kelas->isEmpty())
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Tidak ada kelas yang ditemukan.</p>
    @else
        <ul style="list-style: none; padding: 0;">
            @foreach($kelas as $k)
                <li style="padding: 10px 0; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-chalkboard-user" style="color: var(--accent-color);"></i>
                    <span>{{ $k->nama_kelas }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>

<div class="card">
    <h3 style="margin-bottom: 15px; font-size: 1.1rem; color: var(--text-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Data Jadwal / Mapel ({{ $jadwals->count() }})</h3>
    @if($jadwals->isEmpty())
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Tidak ada jadwal yang ditemukan.</p>
    @else
        <ul style="list-style: none; padding: 0;">
            @foreach($jadwals as $jadwal)
                <li style="padding: 10px 0; border-bottom: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 5px;">
                    <div style="font-weight: 500; color: var(--text-primary);">
                        <i class="fa-solid fa-book" style="color: var(--accent-color); margin-right: 8px;"></i>
                        {{ $jadwal->mapel }}
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); padding-left: 25px;">
                        Hari {{ ucfirst($jadwal->hari) }} | Kelas {{ $jadwal->kelas->nama_kelas }} | Guru: {{ $jadwal->guru->nama }}
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
