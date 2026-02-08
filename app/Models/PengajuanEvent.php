<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PengajuanEvent extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_events';

    protected $fillable = [
        'id_user',
        'id_fasilitas',
        'nama_panitia',
        'nama_event',
        'deskripsi_event',
        'tgl_mulai',
        'tgl_selesai',
        'file_surat',
        'status',
        'alasan_admin',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke Fasilitas
    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas');
    }

    // Helper untuk cek apakah event masih aktif/berjalan
    public function isOngoing()
    {
        return $this->status === 'approved' && $this->tgl_selesai->greaterThanOrEqualTo(Carbon::today());
    }
}
