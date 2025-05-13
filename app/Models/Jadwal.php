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
    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }

    /**
     * Define relationship with Checkout model
     */
    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

    /**
     * Get the formatted date
     */
    public function getFormattedDateAttribute()
    {
        return date('d F Y', strtotime($this->tanggal));
    }

    /**
     * Get the formatted time
     */
    public function getFormattedTimeAttribute()
    {
        return substr($this->jam_mulai, 0, 5) . ' - ' . substr($this->jam_selesai, 0, 5);
    }

    /**
     * Calculate duration in hours
     */
    public function getDurasiAttribute()
    {
        $mulaiParts = explode(':', $this->jam_mulai);
        $selesaiParts = explode(':', $this->jam_selesai);

        $mulaiJam = (int)$mulaiParts[0];
        $selesaiJam = (int)$selesaiParts[0];

        return $selesaiJam - $mulaiJam;
    }
}
