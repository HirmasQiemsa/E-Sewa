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
        'jadwals_id', // Main reference jadwal ID
        'total_bayar',
        'status',
    ];

    /**
     * Define relationship with User model
     */
     // Each checkout belongs to a user
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Each checkout has one main jadwal (for reference)
    public function jadwal() {
        return $this->belongsTo(Jadwal::class, 'jadwals_id');
    }

    // Each checkout has many associated jadwals
    public function jadwals()
    {
        return $this->belongsToMany(Jadwal::class, 'checkout_jadwal')
                    ->using(CheckoutJadwal::class)
                    ->withTimestamps();
    }

    // Each checkout can have multiple payments
    public function pembayaran() {
        return $this->hasMany(PemasukanSewa::class);
    }

    // Each checkout has one history record
    public function riwayat() {
        return $this->hasOne(Riwayat::class);
    }

    // Calculate remaining payment
    public function getSisaPembayaranAttribute() {
        if ($this->status === 'lunas') {
            return 0;
        }

        $totalBayar = $this->total_bayar;
        $sudahDibayar = $this->pembayaran()->sum('jumlah_bayar');

        return $totalBayar - $sudahDibayar;
    }

    /**
     * Get payment status badge
     */
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'fee':
                return '<span class="badge badge-warning">DP</span>';
            case 'lunas':
                return '<span class="badge badge-success">Lunas</span>';
            case 'batal':
                return '<span class="badge badge-danger">Batal</span>';
            default:
                return '<span class="badge badge-secondary">Unknown</span>';
        }
    }
}
