<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fasilitas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fasilitas'; // Nama tabel eksplisit

    protected $fillable = [
        'nama_fasilitas',
        'deskripsi',
        'tipe',
        'kategori',     // Futsal, Tenis, dll
        'lokasi',
        'foto',
        'harga_sewa',
        'ketersediaan',      // aktif, nonaktif, maintenance
        // 'status_approval',   // fitur bagus, tapi di-skip dulu
        'admin_fasilitas_id', // FK ke Admin Pembuat
        'admin_pembayaran_id' // FK ke Admin Keuangan
    ];

    // --- LOGIKA TAMPILAN (ICON & WARNA) ---
    public const CATEGORY_ICONS = [
        'futsal' => ['icon' => 'ion ion-ios-football', 'color' => 'bg-info'],
        'tenis' => ['icon' => 'ion ion-ios-tennisball', 'color' => 'bg-success'],
        'voli' => ['icon' => 'fas fa-volleyball-ball', 'color' => 'bg-warning'],
        'badminton' => ['icon' => 'fas fa-feather', 'color' => 'bg-orange'],
        'basket' => ['icon' => 'fas fa-basketball-ball', 'color' => 'bg-danger'],
        'default' => ['icon' => 'fas fa-hockey-puck', 'color' => 'bg-primary']
    ];

    public function getIconAttribute()
    {
        $kategori = strtolower($this->kategori ?? '');
        return self::CATEGORY_ICONS[$kategori]['icon'] ?? self::CATEGORY_ICONS['default']['icon'];
    }

    public function getIconColorAttribute()
    {
        $kategori = strtolower($this->kategori ?? '');
        return self::CATEGORY_ICONS[$kategori]['color'] ?? self::CATEGORY_ICONS['default']['color'];
    }

    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : asset('img/default-facility.png');
    }

    // --- RELASI ---

    public function jadwals() {
        return $this->hasMany(Jadwal::class);
    }

    public function adminFasilitas() {
        return $this->belongsTo(Admin::class, 'admin_fasilitas_id');
    }

    public function adminPembayaran() {
        return $this->belongsTo(Admin::class, 'admin_pembayaran_id');
    }
}
