<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Guru;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin / Guru Piket
        User::updateOrCreate(
            ['email' => 'admin@sekolah.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Buat Akun Guru
        $guruUser = User::updateOrCreate(
            ['email' => 'guru@sekolah.id'],
            [
                'name' => 'Budi Santoso, S.Pd',
                'password' => Hash::make('password123'),
                'role' => 'guru',
            ]
        );

        // Pastikan guru tersebut juga masuk ke tabel 'gurus'
        Guru::updateOrCreate(
            ['user_id' => $guruUser->id],
            ['nama' => $guruUser->name]
        );
    }
}
