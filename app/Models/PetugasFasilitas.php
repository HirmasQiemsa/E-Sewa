<?php

namespace App\Models;

use App\Traits\HasFotoUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PetugasFasilitas extends Authenticatable
{
    use HasApiTokens, HasFactory, HasFotoUrl, Notifiable;

    protected $fillable = [
        'username', 'name', 'email', 'password', 'role', 'foto', 'no_hp', 'alamat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function fasilitas() {
        return $this->hasMany(Fasilitas::class);
    }
}
