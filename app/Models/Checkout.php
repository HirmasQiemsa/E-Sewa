<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkout extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'jadwals_id', // ID Jadwal Utama (Referensi)
        'total_bayar',
        'status',     // pending, lunas, batal (HAPUS: kompensasi)
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Jadwal Utama
    public function jadwalUtama() {
        return $this->belongsTo(Jadwal::class, 'jadwals_id');
    }

    // Relasi ke BANYAK Jadwal (Pivot)
    public function jadwals()
    {
        return $this->belongsToMany(Jadwal::class, 'checkout_jadwal', 'checkout_id', 'jadwal_id')
                    ->withTimestamps();
    }

    public function pemasukans() {
        return $this->hasMany(Pemasukan::class, 'checkout_id');
    }

    // --- HELPER ATTRIBUTES (UPDATED) ---

    public function getSisaTagihanAttribute()
    {
        if ($this->status == 'lunas') return 0;

        $sudahBayar = $this->pemasukans()
            ->where('status', 'lunas')
            ->sum('jumlah_bayar');

        return max(0, $this->total_bayar - $sudahBayar);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            // 'kompensasi' => '<span class="badge badge-warning">Sudah DP (Belum Lunas)</span>', // DEPRECATED
            'pending'    => '<span class="badge badge-info">Menunggu Verifikasi</span>',
            'lunas'      => '<span class="badge badge-success">Lunas</span>',
            'batal'      => '<span class="badge badge-danger">Batal</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge badge-secondary">Unknown</span>';
    }
}
