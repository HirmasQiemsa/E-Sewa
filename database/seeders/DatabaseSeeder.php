<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Fasilitas; // Jangan lupa import Model Fasilitas

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
            'password' => Hash::make('asd'),
            'role'     => 'super_admin',
            'no_hp'    => '08111111111',
            'alamat'   => 'Kantor Dispora Utama',
            'is_active'=> true,
        ]);

        // 2. Buat Akun Admin Fasilitas (DITAMPUNG KE VARIABEL)
        $adminFasilitas = Admin::create([
            'username' => 'admin_fasilitas',
            'name'     => 'Andi Fasilitas',
            'email'    => 'fasilitas@dispora.com',
            'password' => Hash::make('asd'),
            'role'     => 'admin_fasilitas',
            'no_hp'    => '08122222222',
            'alamat'   => 'Gedung Olahraga A',
            'is_active'=> true,
        ]);

        // 3. Buat Akun Admin Pembayaran (DITAMPUNG KE VARIABEL)
        $adminKeuangan = Admin::create([
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
            'name'     => 'Hinara Santoso',
            'email'    => 'user@example.com',
            'password' => Hash::make('asd'),
            'role'     => 'user',
            'no_hp'    => '081234567890',
            'no_ktp'   => '3374010101010001',
            'alamat'   => 'Jl. Pemuda No. 1, Semarang',
            'is_locked'=> false,
        ]);

        // 5. SEED FASILITAS (Data Awal untuk Beranda)
        // Kita gunakan ID dari admin yang baru saja dibuat di atas

        $listFasilitas = [
        [
            'nama_fasilitas' => 'GOR Futsal Manunggal Jati',
            'lokasi'         => ' Jl. Taman Majapahit Selatan No.1, Pedurungan Lor, Kec. Pedurungan',
            'harga_sewa'     => 100000,
            'ketersediaan'   => 'aktif',
            'kategori'       => 'futsal',
            'foto'           => 'img/fasilitas/futsal-m.jpg',
            'admin_fasilitas_id'  => $adminFasilitas->id,
            'admin_pembayaran_id' => $adminKeuangan->id,
        ],
        [
            'nama_fasilitas' => 'Lapangan Tenis Tri Lomba Juang',
            'lokasi'         => 'Mugassari, Kec. Semarang Sel., Kota Semarang',
            'harga_sewa'     => 250000,
            'ketersediaan'   => 'aktif',
            'kategori'       => 'tenis',
            'foto'           => 'img/fasilitas/tenis-tlj.jpeg',
            'admin_fasilitas_id'  => $adminFasilitas->id,
            'admin_pembayaran_id' => $adminKeuangan->id,
        ],
        [
            'nama_fasilitas' => 'GOR Badminton Tri Lomba Juang',
            'lokasi'         => 'Mugassari, Kec. Semarang Sel., Kota Semarang',
            'harga_sewa'     => 100000,
            'ketersediaan'   => 'aktif',
            'kategori'       => 'bulu tangkis',
            'foto'           => 'img/fasilitas/badminton.jpg',
            'admin_fasilitas_id'  => $adminFasilitas->id,
            'admin_pembayaran_id' => $adminKeuangan->id,
        ],
        [
            'nama_fasilitas' => 'Lapangan Tenis Tambora',
            'lokasi'         => 'Jl. Tambora, Tegalsari, Kec. Candisari, Kota Semarang',
            'harga_sewa'     => 150000,
            'ketersediaan'   => 'aktif',
            'kategori'       => 'tenis',
            'foto'           => 'img/fasilitas/tenis-tambora.jpg',
            'admin_fasilitas_id'  => $adminFasilitas->id,
            'admin_pembayaran_id' => $adminKeuangan->id,
        ],
    ];


        foreach ($listFasilitas as $data) {
            Fasilitas::create($data);
        }

        // Opsional: Tambahkan User Lain untuk Tes
        User::factory(5)->create();
    }
}
