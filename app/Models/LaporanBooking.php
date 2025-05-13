<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkout_id',
        'status',
        'tanggal_booking'
    ];

    public function checkout() {
        return $this->belongsTo(Checkout::class);
    }
}
