@extends('layouts.app')

@section('title', 'Ubah Jadwal')

@section('content')
<div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: flex-end;">
    <div>
        <h1 class="dashboard-title">Ubah Jadwal Pelajaran</h1>
        <p class="dashboard-subtitle">Modifikasi detail jadwal pelajaran.</p>
    </div>
    <div>
        <a href="{{ route('jadwal.index') }}" class="btn" style="background: var(--surface-color); border: 1px solid var(--surface-border); color: var(--text-primary);">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="form-container">
    <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="hari" class="form-label">Hari</label>
            <select name="hari" id="hari" class="form-control" required>
                <option value="Senin" {{ $jadwal->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                <option value="Selasa" {{ $jadwal->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                <option value="Rabu" {{ $jadwal->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                <option value="Kamis" {{ $jadwal->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                <option value="Jumat" {{ $jadwal->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
            </select>
            @error('hari') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="jam" class="form-label">Jam Pelajaran</label>
            <input type="text" name="jam" id="jam" class="form-control" value="{{ $jadwal->jam }}" required>
            @error('jam') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="mapel" class="form-label">Mata Pelajaran</label>
            <input type="text" name="mapel" id="mapel" class="form-control" value="{{ $jadwal->mapel }}" required>
            @error('mapel') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="kelas_id" class="form-label">Kelas</label>
            <select name="kelas_id" id="kelas_id" class="form-control" required>
                @foreach($kelases as $kelas)
                    <option value="{{ $kelas->id }}" {{ $jadwal->kelas_id == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                @endforeach
            </select>
            @error('kelas_id') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="nama_guru" class="form-label">Nama Guru Pengajar (Opsional)</label>
            <input type="text" name="nama_guru" id="nama_guru" class="form-control" value="{{ $jadwal->guru ? $jadwal->guru->nama : '' }}" placeholder="Ketik nama guru (kosongkan jika belum ada)...">
            @error('nama_guru') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 16px;">
            <i class="fa-regular fa-floppy-disk"></i> Simpan Perubahan
        </button>
    </form>
</div>
@endsection
