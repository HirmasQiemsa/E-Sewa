<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Super Admin (Kepala Dinas)
        Admin::create([
            'username' => 'kepala_dinas',
            'name'     => 'Bapak Kepala Dinas',
            'email'    => 'kadin@dispora.com',
            'password' => Hash::make('asd'), // Password default: asd
            'role'     => 'super_admin',
            'no_hp'    => '08111111111',
            'alamat'   => 'Kantor Dispora Utama',
            'is_active'=> true,
        ]);

        // 2. Buat Akun Admin Fasilitas (Staff Fasilitas)
        Admin::create([
            'username' => 'admin_fasilitas',
            'name'     => 'Andi Fasilitas',
            'email'    => 'fasilitas@dispora.com',
            'password' => Hash::make('asd'),
            'role'     => 'admin_fasilitas',
            'no_hp'    => '08122222222',
            'alamat'   => 'Gedung Olahraga A',
            'is_active'=> true,
        ]);

        // 3. Buat Akun Admin Pembayaran (Staff Keuangan)
        Admin::create([
            'username' => 'admin_keuangan',
            'name'     => 'Siti Bendahara',
            'email'    => 'keuangan@dispora.com',
            'password' => Hash::make('asd'),
            'role'     => 'admin_pembayaran',
            'no_hp'    => '08133333333',
            'alamat'   => 'Loket Pembayaran',
            'is_active'=> true,
        ]);

        // 4. Buat Akun User (Masyarakat)
        User::create([
            'username' => 'user',
            'name'     => 'Budi Santoso',
            'email'    => 'user@example.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'no_hp'    => '081234567890',
            'no_ktp'   => '3374010101010001',
            'alamat'   => 'Jl. Pemuda No. 1, Semarang',
            'is_locked'=> false,
        ]);

        // Opsional: Tambahkan User Lain untuk Tes
        User::factory(5)->create(); // generate user acak tambahan
    }
}
