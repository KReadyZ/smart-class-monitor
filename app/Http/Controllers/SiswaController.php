<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\StatusGuru;

class SiswaController extends Controller
{
    public function index()
    {
        $hariIniMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $hariIni = $hariIniMap[date('l')];
        $tanggalHariIni = date('Y-m-d');

        // Fetch all schedules for today with their guru and kelas
        $jadwals = Jadwal::with(['guru', 'kelas'])
            ->where('hari', $hariIni)
            ->orderBy('jam')
            ->get();

        // Attach presence status and keterangan to each schedule
        foreach ($jadwals as $jadwal) {
            $statusGuru = StatusGuru::where('guru_id', $jadwal->guru_id)
                ->where('tanggal', $tanggalHariIni)
                ->first();

            if ($statusGuru) {
                $jadwal->status_kehadiran = $statusGuru->status; // 'hadir' or 'izin'
                $jadwal->keterangan = $statusGuru->keterangan;
            } else {
                $jadwal->status_kehadiran = 'hadir'; // default to 'hadir'
                $jadwal->keterangan = null;
            }
        }

        return view('siswa.pesan', compact('jadwals', 'hariIni', 'tanggalHariIni'));
    }
}
