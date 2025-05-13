<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanRekapPenggunaan extends Model
{
    use HasFactory;

    protected $table = 'laporan_rekap_penggunaans';

    protected $fillable = [
        'checkout_id',
        'jumlah_checkout',
        'bulan',
        'tahun'
    ];

    public function checkout() {
        return $this->belongsTo(Checkout::class);
    }
}
