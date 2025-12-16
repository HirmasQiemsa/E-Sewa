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
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status' // tersedia, terbooking, selesai, batal
    ];

    public function fasilitas() {
        return $this->belongsTo(Fasilitas::class);
    }

    public function checkouts()
    {
        return $this->belongsToMany(Checkout::class, 'checkout_jadwal', 'jadwal_id', 'checkout_id')
                    ->withTimestamps();
    }

    public function getDurasiAttribute() {
        $mulai = (int) explode(':', $this->jam_mulai)[0];
        $selesai = (int) explode(':', $this->jam_selesai)[0];
        return $selesai - $mulai;
    }
}
