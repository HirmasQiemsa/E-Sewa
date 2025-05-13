<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fasilitas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_fasilitas',
        'deskripsi',
        'tipe',
        'lokasi',
        'foto',
        'harga_sewa',
        'ketersediaan'
    ];

    // Mutator atau accessor untuk url
    public function getFotoFasilitasUrlAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : asset('default-user.png');
    }
    protected $appends = ['foto_fasilitas_url'];

    /**
     * Define relationship with Jadwal model
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga_sewa, 0, ',', '.');
    }

    /**
     * Get availability status badge
     */
    public function getStatusBadgeAttribute()
    {
        switch ($this->ketersediaan) {
            case 'aktif':
                return '<span class="badge badge-success">Aktif</span>';
            case 'nonaktif':
                return '<span class="badge badge-danger">Non-Aktif</span>';
            case 'maintanace':
                return '<span class="badge badge-warning">Maintenance</span>';
            default:
                return '<span class="badge badge-secondary">Unknown</span>';
        }
    }

    /**
     * Check if facility is available on a specific date
     */
    public function isAvailableOn($date)
    {
        // Check if facility is active
        if ($this->ketersediaan !== 'aktif') {
            return false;
        }

        // Count available slots on that date
        $availableSlots = $this->jadwals()
                              ->where('tanggal', $date)
                              ->where('status', 'tersedia')
                              ->count();

        return $availableSlots > 0;
    }
}
