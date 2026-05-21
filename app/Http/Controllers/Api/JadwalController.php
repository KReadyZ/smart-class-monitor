<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Guru;
use Illuminate\Support\Facades\Validator;

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

        $jadwals = $query->orderBy('hari')->orderBy('jam')->get();

        return response()->json([
            'status' => 'success',
            'data' => $jadwals
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hari' => 'required|string',
            'jam' => 'required|string',
            'mapel' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'nullable|exists:gurus,id',
            'nama_guru' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $guruId = $request->guru_id;
        if ($request->filled('nama_guru')) {
            // Case-insensitive lookup for guru with registered account (user_id is not null)
            $guru = Guru::whereRaw('LOWER(nama) = ?', [strtolower($request->nama_guru)])
                ->whereNotNull('user_id')
                ->first();
            
            if (!$guru) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Guru dengan nama tersebut belum terdaftar atau belum membuat akun di sistem.'
                ], 422);
            }
            $guruId = $guru->id;
        }

        $jadwal = Jadwal::create([
            'hari' => $request->hari,
            'jam' => $request->jam,
            'mapel' => $request->mapel,
            'kelas_id' => $request->kelas_id,
            'guru_id' => $guruId
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal berhasil ditambahkan.',
            'data' => $jadwal
        ], 201);
    }

    public function show($id)
    {
        $jadwal = Jadwal::with(['guru', 'kelas'])->find($id);

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $jadwal
        ]);
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'hari' => 'sometimes|required|string',
            'jam' => 'sometimes|required|string',
            'mapel' => 'sometimes|required|string',
            'kelas_id' => 'sometimes|required|exists:kelas,id',
            'guru_id' => 'nullable|exists:gurus,id',
            'nama_guru' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        if ($request->filled('nama_guru')) {
            // Case-insensitive lookup for guru with registered account (user_id is not null)
            $guru = Guru::whereRaw('LOWER(nama) = ?', [strtolower($request->nama_guru)])
                ->whereNotNull('user_id')
                ->first();
            
            if (!$guru) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Guru dengan nama tersebut belum terdaftar atau belum membuat akun di sistem.'
                ], 422);
            }
            $data['guru_id'] = $guru->id;
        }

        $jadwal->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal berhasil diperbarui.',
            'data' => $jadwal
        ]);
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal tidak ditemukan'
            ], 404);
        }

        $jadwal->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal berhasil dihapus.'
        ]);
    }
}
