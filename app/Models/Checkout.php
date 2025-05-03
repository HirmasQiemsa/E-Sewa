<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkout extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'fasilitas_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'total_bayar',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fasilitas(): BelongsTo
    {
        return $this->belongsTo(Fasilitas::class);
    }

    public function riwayat(): HasOne
    {
        return $this->hasOne(Riwayat::class);
    }
}
