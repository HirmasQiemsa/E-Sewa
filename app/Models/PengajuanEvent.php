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
        'nama_event',
        'nama_panitia',
        'tgl_mulai',
        'tgl_selesai',
        'deskripsi_event',
        'file_surat',
        'status',
    ];

    protected $casts = [
        'tgl_mulai'   => 'date',
        'tgl_selesai' => 'date',
    ];

    /* ================= RELATION ================= */

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas');
    }

    /* ================= HELPER ================= */

    public function isOngoing()
    {
        return $this->status === 'approved'
            && $this->tgl_selesai->greaterThanOrEqualTo(Carbon::today());
    }
}
