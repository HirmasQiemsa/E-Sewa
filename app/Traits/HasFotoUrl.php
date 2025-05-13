<?php

namespace App\Traits;

trait HasFotoUrl
{
    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : asset('default-user.png');
    }

    // Supaya 'foto_url' muncul otomatis saat model dikonversi ke array/JSON
    public function initializeHasFotoUrl()
    {
        $this->appends = array_unique(array_merge($this->appends ?? [], ['foto_url']));
    }
}
