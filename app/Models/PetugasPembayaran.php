<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasFotoUrl;

class PetugasPembayaran extends Authenticatable
{
    use HasApiTokens, HasFactory, HasFotoUrl, Notifiable;

    protected $fillable = [
        'username', 'name', 'email', 'password', 'role', 'foto', 'no_hp', 'alamat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pemasukanSewas() {
        return $this->hasMany(PemasukanSewa::class);
    }
}
