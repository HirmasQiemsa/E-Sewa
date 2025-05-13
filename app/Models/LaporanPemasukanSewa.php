<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPemasukanSewa extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemasukan_sewa_id',
        'bulan',
        'tahun',
        'total_pemasukan'
    ];

    public function pemasukanSewa() {
        return $this->belongsTo(PemasukanSewa::class);
    }
}
