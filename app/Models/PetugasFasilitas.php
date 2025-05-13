<?php

namespace App\Models;

use App\Traits\HasFotoUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasFasilitas extends Model
{
    use HasFactory,HasFotoUrl;

    protected $fillable = [
        'username', 'name', 'email', 'password', 'role','foto','no_hp','alamat',
    ];
}
