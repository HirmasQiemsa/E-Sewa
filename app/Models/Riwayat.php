<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Riwayat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'checkout_id',
        'waktu_selesai',
        'feedback'
    ];

    /**
     * Define relationship with Checkout model
     */
    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

    /**
     * Get formatted completion time
     */
    public function getFormattedTimeAttribute()
    {
        return date('d F Y H:i', strtotime($this->waktu_selesai));
    }
}
