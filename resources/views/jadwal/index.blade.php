@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
    <div>
        <h1 class="dashboard-title" style="font-size: 1.75rem; font-weight: 700; color: #111827; margin-bottom: 8px;">Jadwal Pelajaran</h1>
        <p class="dashboard-subtitle" style="color: #6b7280; font-size: 0.95rem; margin: 0;">Kelola dan pantau seluruh jadwal kegiatan belajar mengajar akademik.</p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        <a href="{{ route('jadwal.create') }}" class="btn btn-primary" style="background: #0d6efd; color: white; border-radius: 6px; padding: 10px 16px; text-decoration: none; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; border: none;">
            <i class="fa-solid fa-plus"></i> Tambah Jadwal
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: 6px; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="panel" style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 24px; padding: 20px 24px;">
    <form action="{{ route('jadwal.index') }}" method="GET" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; gap: 24px; flex: 1;">
            <div style="width: 220px;">
                <div style="position: relative;">
                    <select name="hari" id="hari" class="form-control" onchange="this.form.submit()" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #f9fafb; font-size: 0.9rem; color: #374151; appearance: none; cursor: pointer;">
                        <option value="">Semua Hari</option>
                        <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                    </select>
                    <i class="fa-solid fa-chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.8rem; pointer-events: none;"></i>
                </div>
            </div>
            <div style="width: 220px;">
                <div style="position: relative;">
                    <select name="kelas_id" id="kelas_id" class="form-control" onchange="this.form.submit()" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #f9fafb; font-size: 0.9rem; color: #374151; appearance: none; cursor: pointer;">
                        <option value="">Semua Kelas</option>
                        @foreach($kelases as $kelas)
                            <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.8rem; pointer-events: none;"></i>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('jadwal.index') }}" style="color: #0d6efd; text-decoration: none; font-weight: 500; font-size: 0.9rem;">Reset Filter</a>
        </div>
    </form>
</div>

<div class="panel" style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
    @if(count($jadwals) > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="border-bottom: 1px solid #e5e7eb;">
                    <tr>
                        <th style="padding: 16px 24px; font-size: 0.75rem; font-weight: 700; color: #6b7280; letter-spacing: 0.05em; text-transform: uppercase;">HARI</th>
                        <th style="padding: 16px 24px; font-size: 0.75rem; font-weight: 700; color: #6b7280; letter-spacing: 0.05em; text-transform: uppercase;">JAM</th>
                        <th style="padding: 16px 24px; font-size: 0.75rem; font-weight: 700; color: #6b7280; letter-spacing: 0.05em; text-transform: uppercase;">KELAS</th>
                        <th style="padding: 16px 24px; font-size: 0.75rem; font-weight: 700; color: #6b7280; letter-spacing: 0.05em; text-transform: uppercase;">MATA PELAJARAN</th>
                        <th style="padding: 16px 24px; font-size: 0.75rem; font-weight: 700; color: #6b7280; letter-spacing: 0.05em; text-transform: uppercase;">GURU</th>
                        <th style="padding: 16px 24px; font-size: 0.75rem; font-weight: 700; color: #6b7280; letter-spacing: 0.05em; text-transform: uppercase;">STATUS</th>
                        <th style="padding: 16px 24px; font-size: 0.75rem; font-weight: 700; color: #6b7280; letter-spacing: 0.05em; text-transform: uppercase; text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwals as $jadwal)
                    @php
                        // Calculate status
                        $hari_map = [
                            'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 0
                        ];
                        $currentDay = \Carbon\Carbon::now()->dayOfWeek;
                        $jadwalDay = $hari_map[$jadwal->hari] ?? -1;
                        $currentTime = \Carbon\Carbon::now()->format('H:i');
                        
                        $parts = explode('-', $jadwal->jam);
                        $start_time = str_replace('.', ':', trim($parts[0] ?? '00:00'));
                        $end_time = str_replace('.', ':', trim($parts[1] ?? '23:59'));

                        $status = 'Menunggu';
                        $statusBg = '#fef3c7';
                        $statusColor = '#d97706';
                        $statusBorder = '#fde68a';
                        
                        if ($currentDay == $jadwalDay) {
                            if ($currentTime < $start_time) {
                                $status = 'Menunggu';
                                $statusBg = '#fef3c7';
                                $statusColor = '#d97706';
                                $statusBorder = '#fde68a';
                            } elseif ($currentTime >= $start_time && $currentTime <= $end_time) {
                                $status = 'Sedang Berlangsung';
                                $statusBg = '#eff6ff';
                                $statusColor = '#2563eb';
                                $statusBorder = '#bfdbfe';
                            } else {
                                $status = 'Selesai';
                                $statusBg = '#f3f4f6';
                                $statusColor = '#6b7280';
                                $statusBorder = '#e5e7eb';
                            }
                        } elseif ($currentDay > 0 && $currentDay > $jadwalDay) {
                            $status = 'Selesai';
                            $statusBg = '#f3f4f6';
                            $statusColor = '#6b7280';
                            $statusBorder = '#e5e7eb';
                        } elseif ($currentDay == 0 && $jadwalDay > 0) {
                            $status = 'Selesai';
                            $statusBg = '#f3f4f6';
                            $statusColor = '#6b7280';
                            $statusBorder = '#e5e7eb';
                        }

                        // Determine circle color for teacher initial
                        $colors = ['#dbeafe', '#fef08a', '#f3e8ff', '#dcfce3', '#ffedd5', '#fce7f3'];
                        $textColors = ['#1e40af', '#854d0e', '#6b21a8', '#166534', '#9a3412', '#9d174d'];
                        $hash = crc32($jadwal->guru ? $jadwal->guru->nama : 'X');
                        $colorIndex = abs($hash) % count($colors);
                        $bgColor = $colors[$colorIndex];
                        $textColor = $textColors[$colorIndex];
                        $initial = strtoupper(substr($jadwal->guru ? $jadwal->guru->nama : 'X', 0, 1));
                    @endphp
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 16px 24px; font-weight: 600; color: #111827; font-size: 0.95rem;">{{ $jadwal->hari }}</td>
                        <td style="padding: 16px 24px; color: #4b5563; font-size: 0.95rem;">{{ $jadwal->jam }}</td>
                        <td style="padding: 16px 24px; font-weight: 700; color: #4b5563; font-size: 0.95rem;">
                            {{ $jadwal->kelas ? $jadwal->kelas->nama_kelas : '-' }}
                        </td>
                        <td style="padding: 16px 24px; font-weight: 700; color: #111827; font-size: 0.95rem;">{{ $jadwal->mapel }}</td>
                        <td style="padding: 16px 24px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 26px; height: 26px; background: {{ $bgColor }}; color: {{ $textColor }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">
                                    {{ $initial }}
                                </div>
                                <span style="font-size: 0.95rem; color: #4b5563;">{{ $jadwal->guru ? $jadwal->guru->nama : '-' }}</span>
                            </div>
                        </td>
                        <td style="padding: 16px 24px;">
                            <span style="display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; background-color: {{ $statusBg }}; color: {{ $statusColor }}; border: 1px solid {{ $statusBorder }};">
                                {{ $status }}
                            </span>
                        </td>
                        <td style="padding: 16px 24px; text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <a href="{{ route('jadwal.edit', $jadwal->id) }}" style="color: #6b7280; background: #f3f4f6; padding: 6px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #fef2f2; border: none; color: #ef4444; cursor: pointer; padding: 6px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div style="padding: 16px 24px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; background: #fff;">
            <div style="font-size: 0.85rem; color: #6b7280;">
                Menampilkan {{ $jadwals->firstItem() ?? 0 }}-{{ $jadwals->lastItem() ?? 0 }} dari {{ $jadwals->total() }} entri
            </div>
            <div>
                {{ $jadwals->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @else
        <div style="text-align: center; color: #6b7280; padding: 64px 16px;">
            <i class="fa-regular fa-calendar-xmark" style="font-size: 3rem; margin-bottom: 16px; color: #d1d5db;"></i>
            <h3 style="font-size: 1.1rem; color: #111827; margin-bottom: 8px; font-weight: 600;">Tidak ada jadwal pelajaran ditemukan.</h3>
            <p style="font-size: 0.9rem;">Silakan tambahkan jadwal baru atau ubah filter.</p>
        </div>
    @endif
</div>

<style>
    /* Custom simple pagination styles if bootstrap is not fully loaded for it */
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 6px;
    }
    .pagination li a, .pagination li span, .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        font-size: 0.85rem;
        color: #374151;
        text-decoration: none;
        background: white;
    }
    .pagination li.active span, .pagination li.active .page-link, .page-item.active .page-link {
        background: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    .pagination li.disabled span, .pagination li.disabled .page-link, .page-item.disabled .page-link {
        color: #9ca3af;
        background: #f9fafb;
    }
    .page-item:first-child .page-link, .page-item:last-child .page-link {
        border-radius: 4px;
    }
</style>
@endsection
