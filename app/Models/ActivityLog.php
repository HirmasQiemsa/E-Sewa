<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'activity_logs';

    protected $fillable = [
        'admin_id',
        'user_id',
        'action',       // CREATE, UPDATE, DELETE, LOGIN, etc
        'description',
        'subject_type', // Polymorphic (Model Class)
        'subject_id'    // ID Model
    ];

    public function admin() {
        return $this->belongsTo(Admin::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    // Mengambil objek terkait (Misal: Data Fasilitas yang diedit)
    public function subject() {
        return $this->morphTo();
    }
}
