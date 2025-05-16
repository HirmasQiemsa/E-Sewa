<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadwals';

    protected $fillable = [
        'fasilitas_id',
        'checkout_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status'
    ];

    /**
     * Define relationship with Fasilitas model
     */
// Each schedule belongs to a facility
    public function fasilitas() {
        return $this->belongsTo(Fasilitas::class);
    }

    // Each schedule can belong to a checkout
    public function checkout() {
        return $this->belongsTo(Checkout::class);
    }

    /**
     * The checkouts that belong to the jadwal.
     */
    public function checkouts()
    {
        return $this->belongsToMany(Checkout::class, 'checkout_jadwal')
                    ->using(CheckoutJadwal::class)
                    ->withTimestamps();
    }

    // Calculate duration in hours
    public function getDurasiAttribute() {
        $mulaiParts = explode(':', $this->jam_mulai);
        $selesaiParts = explode(':', $this->jam_selesai);

        $mulaiJam = (int)$mulaiParts[0];
        $selesaiJam = (int)$selesaiParts[0];

        return $selesaiJam - $mulaiJam;
    }
}
