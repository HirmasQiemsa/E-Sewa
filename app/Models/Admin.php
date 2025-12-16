<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasFotoUrl; // Pastikan trait ini ada

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, HasFotoUrl, Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',         // 'super_admin', 'admin_fasilitas', 'admin_pembayaran'
        'foto',
        'no_hp',
        'alamat',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // --- RELASI ---

    // Relasi: Fasilitas yang DIBUAT oleh admin ini (Role: Admin Fasilitas)
    public function fasilitasDibuat() {
        return $this->hasMany(Fasilitas::class, 'admin_fasilitas_id');
    }

    // Relasi: Fasilitas yang DIKELOLA KEUANGANNYA oleh admin ini (Role: Admin Pembayaran)
    public function fasilitasDitangani() {
        return $this->hasMany(Fasilitas::class, 'admin_pembayaran_id');
    }

    // Relasi: Pembayaran yang DIVALIDASI oleh admin ini
    public function pemasukans() {
        return $this->hasMany(Pemasukan::class, 'admin_id');
    }

    // Relasi: Log aktivitas admin
    public function activityLogs() {
        return $this->hasMany(ActivityLog::class);
    }

    // --- HELPER ---

    public function isSuperAdmin() {
        return $this->role === 'super_admin';
    }

    public function isAdminFasilitas() {
        return $this->role === 'admin_fasilitas';
    }

    public function isAdminPembayaran() {
        return $this->role === 'admin_pembayaran';
    }
}
