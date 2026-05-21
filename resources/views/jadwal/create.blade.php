@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: flex-end;">
    <div>
        <h1 class="dashboard-title">Tambah Jadwal Baru</h1>
        <p class="dashboard-subtitle">Masukkan detail jadwal pelajaran baru.</p>
    </div>
    <div>
        <a href="{{ route('jadwal.index') }}" class="btn" style="background: var(--surface-color); border: 1px solid var(--surface-border); color: var(--text-primary);">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="form-container">
    <form action="{{ route('jadwal.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="hari" class="form-label">Hari</label>
            <select name="hari" id="hari" class="form-control" required>
                <option value="">-- Pilih Hari --</option>
                <option value="Senin">Senin</option>
                <option value="Selasa">Selasa</option>
                <option value="Rabu">Rabu</option>
                <option value="Kamis">Kamis</option>
                <option value="Jumat">Jumat</option>
            </select>
            @error('hari') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="jam" class="form-label">Jam Pelajaran</label>
            <input type="text" name="jam" id="jam" class="form-control" placeholder="Contoh: 07:30 - 09:00" required>
            @error('jam') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="mapel" class="form-label">Mata Pelajaran</label>
            <input type="text" name="mapel" id="mapel" class="form-control" placeholder="Contoh: Matematika" required>
            @error('mapel') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="kelas_id" class="form-label">Kelas</label>
            <select name="kelas_id" id="kelas_id" class="form-control" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelases as $kelas)
                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                @endforeach
            </select>
            @error('kelas_id') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="nama_guru" class="form-label">Nama Guru Pengajar (Opsional)</label>
            <input type="text" name="nama_guru" id="nama_guru" class="form-control" placeholder="Ketik nama guru (kosongkan jika belum ada)...">
            @error('nama_guru') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 16px;">
            <i class="fa-regular fa-floppy-disk"></i> Simpan Jadwal
        </button>
    </form>
</div>
@endsection
