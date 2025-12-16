<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;

    protected $table = 'pemasukans'; // Sesuai migrasi baru

    protected $fillable = [
        'checkout_id',
        'fasilitas_id',
        'user_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_pembayaran',
        'bukti_bayar',
        'status',             // lunas, pending, ditolak
        'keterangan',
        'admin_id'            // Admin yang memvalidasi
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function checkout() {
        return $this->belongsTo(Checkout::class);
    }

    public function fasilitas() {
        return $this->belongsTo(Fasilitas::class);
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_bayar, 0, ',', '.');
    }
}
