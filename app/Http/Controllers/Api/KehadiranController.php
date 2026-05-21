<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $query = Kehadiran::with(['jadwal.guru', 'jadwal.kelas']);
        
        if ($request->has('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        } else {
            // Default to today
            $query->whereDate('created_at', Carbon::today());
        }

        $kehadiran = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $kehadiran
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_id' => 'required|exists:jadwals,id',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string',
            'ruang_kelas' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $kehadiran = Kehadiran::create([
            'jadwal_id' => $request->jadwal_id,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'ruang_kelas' => $request->ruang_kelas,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data kehadiran berhasil disimpan.',
            'data' => $kehadiran
        ], 201);
    }

    public function show($id)
    {
        $kehadiran = Kehadiran::with(['jadwal.guru', 'jadwal.kelas'])->find($id);

        if (!$kehadiran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data kehadiran tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $kehadiran
        ]);
    }

    public function update(Request $request, $id)
    {
        $kehadiran = Kehadiran::find($id);

        if (!$kehadiran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data kehadiran tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string',
            'ruang_kelas' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $kehadiran->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data kehadiran berhasil diperbarui.',
            'data' => $kehadiran
        ]);
    }

    public function destroy($id)
    {
        $kehadiran = Kehadiran::find($id);

        if (!$kehadiran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data kehadiran tidak ditemukan'
            ], 404);
        }

        $kehadiran->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data kehadiran berhasil dihapus.'
        ]);
    }
}
