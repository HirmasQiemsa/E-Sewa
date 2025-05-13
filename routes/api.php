<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Jadwal;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// routes/api.php
Route::get('/jadwal', function(Request $request) {
    $fasilitasId = $request->query('fasilitas_id');
    $tanggal = $request->query('tanggal');

    if (!$fasilitasId || !$tanggal) {
        return response()->json(['error' => 'Parameter tidak lengkap'], 400);
    }

    // Ambil jadwal dari database
    $jadwals = Jadwal::where('fasilitas_id', $fasilitasId)
                    ->where('tanggal', $tanggal)
                    ->get();

    return response()->json($jadwals);
});
