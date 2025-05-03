<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fasilitas extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'foto',
        'nama_fasilitas',
        'deskripsi',
        'tipe',
        'lokasi',
        'harga',
        'tersedia',
    ];

    protected static function booted()
    {
        static::deleting(function ($fasilitas) {
            // Jika soft delete, ubah tersedia jadi false
            if (!$fasilitas->isForceDeleting()) {
                $fasilitas->tersedia = false;
                $fasilitas->save();
            }
        });

        static::restoring(function ($fasilitas) {
            // Saat restore, ubah tersedia jadi true
            $fasilitas->tersedia = true;
        });
    }

    public function getFotoFasilitasUrlAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : asset('default-user.png');
    }

    protected $appends = ['foto_fasilitas_url'];

    public function checkouts(): HasMany
    {
        return $this->hasMany(Checkout::class);
    }

}
