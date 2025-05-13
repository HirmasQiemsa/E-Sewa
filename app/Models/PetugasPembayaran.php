<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PemasukanSewa;
use App\Traits\HasFotoUrl;

class PetugasPembayaran extends Model
{
    use HasFactory,HasFotoUrl;

    protected $fillable = [
        'username', 'name', 'email', 'password', 'role','foto','no_hp','alamat',
    ];

    public function pemasukanSewas() {
        return $this->hasMany(PemasukanSewa::class);
    }

}
