<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\StatusGuru;
use App\Models\Jadwal;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();
        $guruIzinHariIni = StatusGuru::where('tanggal', date('Y-m-d'))
                                    ->where('status', 'izin')
                                    ->count();

        // Get some active classes/schedules for monitoring table
        $jadwals = Jadwal::with(['guru', 'kelas'])->limit(4)->get();
        
        // Get recent teacher statuses for updates panel
        $pembaruanGuru = StatusGuru::with('guru')
                                   ->where('tanggal', date('Y-m-d'))
                                   ->latest()
                                   ->limit(3)
                                   ->get();
                                   
        $semuaStatusHariIni = StatusGuru::with('guru')
                                   ->where('tanggal', date('Y-m-d'))
                                   ->get();

        return view('dashboard', compact('totalGuru', 'totalKelas', 'guruIzinHariIni', 'jadwals', 'pembaruanGuru', 'semuaStatusHariIni'));
    }
}
