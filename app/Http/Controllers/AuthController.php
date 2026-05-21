<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Guru;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->intended('dashboard');
            } elseif ($user->role === 'guru') {
                return redirect()->intended('kehadiran');
            } elseif ($user->role === 'siswa') {
                return redirect()->intended('pesan');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,guru,siswa'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Validasi Kode Akses
        if ($request->role === 'admin' && $request->secret_code !== 'ADMINSKENSA2026') {
            return back()->withErrors(['secret_code' => 'Kode akses Admin salah!'])->withInput();
        }
        
        if ($request->role === 'guru' && $request->secret_code !== 'GURUSKENSA2026') {
            return back()->withErrors(['secret_code' => 'Kode akses Guru salah!'])->withInput();
        }

        $fotoPath = null;
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $fotoPath = $file->storeAs('profile_photos', $filename, 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'foto_profil' => $fotoPath,
        ]);

        if ($user->role === 'guru') {
            Guru::create([
                'user_id' => $user->id,
                'nama' => $user->name,
            ]);
        }

        Auth::login($user);

        if ($user->role === 'admin') {
            return redirect('dashboard');
        } elseif ($user->role === 'guru') {
            return redirect('kehadiran');
        } elseif ($user->role === 'siswa') {
            return redirect('pesan');
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
