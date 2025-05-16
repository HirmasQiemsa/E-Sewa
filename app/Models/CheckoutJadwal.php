<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CheckoutJadwal extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'checkout_jadwal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'checkout_id',
        'jadwal_id',
    ];

    /**
     * Get the checkout that this pivot belongs to.
     */
    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

    /**
     * Get the jadwal that this pivot belongs to.
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
