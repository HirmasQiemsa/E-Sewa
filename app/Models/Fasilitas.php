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
        'admin_fasilitas_id', // FK ke Admin Pembuat
        'admin_pembayaran_id' // FK ke Admin Keuangan
    ];

    // --- LOGIKA TAMPILAN (ICON & WARNA) ---
    public const CATEGORY_ICONS = [
        'futsal' => ['icon' => 'ion ion-ios-football', 'color' => 'bg-info'],
        'tenis' => ['icon' => 'ion ion-ios-tennisball', 'color' => 'bg-success'],
        'voli' => ['icon' => 'fas fa-volleyball-ball', 'color' => 'bg-warning'],
        'bulutangkis' => ['icon' => 'fas fa-feather', 'color' => 'bg-orange'],
        'sepak bola' => ['icon' => 'fas fa-futbol', 'color' => 'bg-danger'],
        // 'basket' => ['icon' => 'fas fa-basketball-ball', 'color' => 'bg-danger'],
        'default' => ['icon' => 'fas fa-hockey-puck', 'color' => 'bg-primary']
    ];

    public function getStatusUIAttribute($date = null)
    {
        $date = $date ?? now()->format('Y-m-d');
        $isToday = $date == now()->format('Y-m-d');
        $currentTime = now()->format('H:i:s');


        $validJadwals = $this->jadwals->filter(function ($j) use ($isToday, $currentTime) {
            return $isToday ? $j->jam_mulai > $currentTime : true;
        });

        $totalValid = $validJadwals->count();


        if ($this->ketersediaan !== 'aktif') return 'closed';
        if ($this->jadwals_count === 0) return 'closed'; // Pakai hasil withCount
        if ($totalValid === 0) return 'full';

        return 'available';
    }

    
    public function getUiMetaData($statusType)
    {
        $catKey = strtolower($this->kategori ?? 'default');
        $style = self::CATEGORY_ICONS[$catKey] ?? self::CATEGORY_ICONS['default'];

        $meta = [
            'closed' => ['class' => 'bg-secondary', 'text' => 'Tidak Tersedia', 'icon' => 'fas fa-ban'],
            'locked' => ['class' => 'bg-secondary', 'text' => 'Terkunci', 'icon' => 'fas fa-lock'],
            'full'   => ['class' => $style['color'], 'text' => 'Jadwal Habis', 'icon' => 'fas fa-calendar-times'],
            'available' => ['class' => $style['color'], 'text' => 'Tersedia', 'icon' => 'far fa-clock'],
        ];

        return $meta[$statusType] ?? $meta['available'];
    }

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
