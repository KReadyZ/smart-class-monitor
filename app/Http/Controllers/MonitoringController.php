<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\StatusGuru;

class MonitoringController extends Controller
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

        // Get id of gurus who are 'izin' today
        $guruIzinIds = StatusGuru::where('tanggal', $tanggalHariIni)
            ->where('status', 'izin')
            ->pluck('guru_id');

        $kelasKosong = Jadwal::with(['guru', 'kelas'])
            ->where('hari', $hariIni)
            ->whereIn('guru_id', $guruIzinIds)
            ->orderBy('jam')
            ->get();

        // Attach keterangan
        foreach($kelasKosong as $kelas) {
            $status = StatusGuru::where('guru_id', $kelas->guru_id)
                ->where('tanggal', $tanggalHariIni)
                ->where('status', 'izin')
                ->first();
            $kelas->keterangan = $status ? $status->keterangan : '-';
        }

        return view('monitoring.index', compact('kelasKosong', 'hariIni', 'tanggalHariIni'));
    }
}
