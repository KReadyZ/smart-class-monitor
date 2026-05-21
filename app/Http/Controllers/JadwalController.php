<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Kelas;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::with(['guru', 'kelas']);
        
        if ($request->has('hari') && $request->hari != '') {
            $query->where('hari', $request->hari);
        }

        if ($request->has('kelas_id') && $request->kelas_id != '') {
            $query->where('kelas_id', $request->kelas_id);
        }

        $jadwals = $query->orderBy('hari')->orderBy('jam')->paginate(10)->withQueryString();
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();

        return view('jadwal.index', compact('jadwals', 'kelases'));
    }

    public function create()
    {
        // Pastikan kelas-kelas ini ada untuk testing/awal
        $kelasNames = ['XI RPL 1', 'XI RPL 2', 'XI DKV 1', 'XI DKV 2', 'XI TKJ 1', 'XI TKJ 2', 'XI TKJ 3'];
        foreach($kelasNames as $name) {
            Kelas::firstOrCreate(['nama_kelas' => $name]);
        }
        
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();
        return view('jadwal.create', compact('kelases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|string',
            'jam' => 'required|string',
            'mapel' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'nama_guru' => 'nullable|string'
        ]);

        $guruId = null;
        if ($request->filled('nama_guru')) {
            // Case-insensitive lookup for guru with registered account (user_id is not null)
            $guru = Guru::whereRaw('LOWER(nama) = ?', [strtolower($request->nama_guru)])
                ->whereNotNull('user_id')
                ->first();
            
            if (!$guru) {
                return back()->withErrors(['nama_guru' => 'Guru dengan nama tersebut belum terdaftar atau belum membuat akun di sistem.'])->withInput();
            }
            $guruId = $guru->id;
        }

        Jadwal::create([
            'hari' => $request->hari,
            'jam' => $request->jam,
            'mapel' => $request->mapel,
            'kelas_id' => $request->kelas_id,
            'guru_id' => $guruId
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        // Pastikan kelas-kelas ini ada
        $kelasNames = ['XI RPL 1', 'XI RPL 2', 'XI DKV 1', 'XI DKV 2', 'XI TKJ 1', 'XI TKJ 2', 'XI TKJ 3'];
        foreach($kelasNames as $name) {
            Kelas::firstOrCreate(['nama_kelas' => $name]);
        }
        
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();
        return view('jadwal.edit', compact('jadwal', 'kelases'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'hari' => 'required|string',
            'jam' => 'required|string',
            'mapel' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'nama_guru' => 'nullable|string'
        ]);

        $guruId = null;
        if ($request->filled('nama_guru')) {
            // Case-insensitive lookup for guru with registered account (user_id is not null)
            $guru = Guru::whereRaw('LOWER(nama) = ?', [strtolower($request->nama_guru)])
                ->whereNotNull('user_id')
                ->first();
            
            if (!$guru) {
                return back()->withErrors(['nama_guru' => 'Guru dengan nama tersebut belum terdaftar atau belum membuat akun di sistem.'])->withInput();
            }
            $guruId = $guru->id;
        }

        $jadwal->update([
            'hari' => $request->hari,
            'jam' => $request->jam,
            'mapel' => $request->mapel,
            'kelas_id' => $request->kelas_id,
            'guru_id' => $guruId
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
