<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanFasilitas extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkout_id',
        'fasilitas_id',
        'user_id',
        'jumlah_jam'
    ];

    public function checkout() {
        return $this->belongsTo(Checkout::class);
    }

    public function fasilitas() {
        return $this->belongsTo(Fasilitas::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
