<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasFotoUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasFotoUrl;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        'username', 'name', 'password', 'alamat', 'no_hp', 'role','foto','email','no_ktp'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Mutator atau accessor untuk url
    // public function getUserProfilePhotoUrlAttribute()
    // {
    //     return $this->profile_photo_path ? asset('storage/' . $this->profile_photo_path) : asset('default-avatar.png');
    // }

    // relasi
    // Each facility has many schedules
    public function jadwals() {
        return $this->hasMany(Jadwal::class);
    }

    // Method to check availability on a specific date
    public function isAvailableOn($date) {
        if ($this->ketersediaan !== 'aktif') {
            return false;
        }

        $availableSlots = $this->jadwals()
                          ->where('tanggal', $date)
                          ->where('status', 'tersedia')
                          ->count();

        return $availableSlots > 0;
    }
}
