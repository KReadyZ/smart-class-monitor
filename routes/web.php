<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'admin') return redirect()->route('dashboard');
        if ($role === 'guru') return redirect()->route('kehadiran.create');
        if ($role === 'siswa') return redirect()->route('siswa.pesan');
    }
    return redirect()->route('login');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin / Guru Piket
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('jadwal', JadwalController::class);
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    
    // Guru
    Route::get('/kehadiran', [KehadiranController::class, 'create'])->name('kehadiran.create');
    Route::post('/kehadiran', [KehadiranController::class, 'store'])->name('kehadiran.store');
    
    // Siswa
    Route::get('/pesan', [SiswaController::class, 'index'])->name('siswa.pesan');

    // General Features
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');
    
    Route::get('/pengaturan', [App\Http\Controllers\SettingController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/update', [App\Http\Controllers\SettingController::class, 'updateProfile'])->name('pengaturan.update');
    Route::post('/pengaturan/hapus-foto', [App\Http\Controllers\SettingController::class, 'hapusFoto'])->name('pengaturan.hapus_foto');
});

