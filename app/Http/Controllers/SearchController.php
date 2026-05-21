<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jadwal;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return redirect()->back();
        }

        $gurus = Guru::where('nama', 'LIKE', "%{$query}%")->get();
        $kelas = Kelas::where('nama_kelas', 'LIKE', "%{$query}%")->get();
        $jadwals = Jadwal::with(['guru', 'kelas'])->where('mapel', 'LIKE', "%{$query}%")->get();

        return view('search.results', compact('gurus', 'kelas', 'jadwals', 'query'));
    }
}
