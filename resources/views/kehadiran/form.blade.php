@extends('layouts.app')

@section('title', 'Form Kehadiran & Izin')
@section('header', 'Status Kehadiran Guru')

@section('content')
<div class="dashboard-header" style="margin-bottom: 32px;">
    <h1 class="dashboard-title">Formulir Status Kehadiran</h1>
    <p class="dashboard-subtitle">Sampaikan laporan ketidakhadiran atau pesan untuk siswa.</p>
</div>

<div class="form-container">
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('kehadiran.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nama_guru" class="form-label">Nama Guru</label>
            <input type="text" name="nama_guru" id="nama_guru" class="form-control" placeholder="Ketik nama guru..." required>
            @error('nama_guru') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="ruang_kelas" class="form-label">Ruang Kelas / Bengkel / Lab </label>
            <input type="text" name="ruang_kelas" id="ruang_kelas" class="form-control" placeholder="Contoh: XI RPL 2, Lab Komputer 1..." required>
            @error('ruang_kelas') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="status" class="form-label">Status Kehadiran Hari Ini</label>
            <select name="status" id="status" class="form-control" required onchange="toggleKeterangan()">
                <option value="hadir">Hadir</option>
                <option value="izin">Izin / Berhalangan</option>
            </select>
            @error('status') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group animate-fade-in" id="keterangan_group" style="display: none;">
            <label for="keterangan" class="form-label">
                Keterangan / Alasan Izin 
                <small style="color: var(--text-secondary); font-weight: normal;">(Tuliskan pesan atau alasan mengapa meminta izin, misal: Sakit, Urusan Keluarga, atau tugas yang harus dikerjakan siswa)</small>
            </label>
            <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Tulis keterangan atau tugas pengganti di sini..."></textarea>
            @error('keterangan') <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">
            <i class="fa-regular fa-paper-plane"></i> Kirim Status Kehadiran
        </button>
    </form>
</div>

<script>
    function toggleKeterangan() {
        const status = document.getElementById('status').value;
        const keteranganGroup = document.getElementById('keterangan_group');
        
        if (status === 'izin') {
            keteranganGroup.style.display = 'block';
        } else {
            keteranganGroup.style.display = 'none';
        }
    }
</script>
@endsection
