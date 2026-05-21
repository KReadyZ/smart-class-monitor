<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\StatusGuru;

class KehadiranController extends Controller
{
    public function create()
    {
        return view('kehadiran.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required|string',
            'status' => 'required|in:hadir,izin',
            'ruang_kelas' => 'nullable|string',
            'keterangan' => 'nullable|string'
        ]);

        $guru = Guru::firstOrCreate(['nama' => $request->nama_guru]);

        StatusGuru::updateOrCreate(
            [
                'guru_id' => $guru->id,
                'tanggal' => date('Y-m-d'),
            ],
            [
                'status' => $request->status,
                'ruang_kelas' => $request->ruang_kelas,
                'keterangan' => $request->keterangan
            ]
        );

        return redirect()->back()->with('success', 'Status kehadiran berhasil disimpan!');
    }
}
