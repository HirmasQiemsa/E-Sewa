<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasFotoUrl;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasFotoUrl, Notifiable;

    protected $fillable = [
        'username', 'name', 'password', 'alamat', 'no_hp',
        'role', 'foto', 'email', 'no_ktp',
        'is_locked' // Kolom baru untuk fitur Banned/Lock
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_locked' => 'boolean',
    ];

    // Relasi ke Checkout (Riwayat Booking)
    public function checkouts() {
        return $this->hasMany(Checkout::class);
    }

    // Relasi ke Activity Logs (Jika user melakukan aksi yang dicatat)
    public function activityLogs() {
        return $this->hasMany(ActivityLog::class);
    }
}
