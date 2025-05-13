<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemasukanSewa extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkout_id',
        'fasilitas_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_pembayaran',
        'status',
        'keterangan'
    ];

    /**
     * Define relationship with Checkout model
     */
    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

    /**
     * Define relationship with Fasilitas model
     */
    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }

    /**
     * Get formatted payment amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_bayar, 0, ',', '.');
    }

    /**
     * Get formatted payment date
     */
    public function getFormattedDateAttribute()
    {
        return date('d F Y H:i', strtotime($this->tanggal_bayar));
    }
}
