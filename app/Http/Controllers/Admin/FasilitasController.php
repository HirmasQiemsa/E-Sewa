<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Checkout;
use App\Models\Fasilitas;
use App\Models\User;

class FasilitasController extends Controller
{
// Menampilkan Halaman
    public function index()
    {
        $data = Fasilitas::withTrashed()->get();
        return view('Admin.Fasilitas.index',compact('data'));
    }
    public function tambah()
    {
        return view('Admin.Fasilitas.tambah');
    }


// Fungsi untuk menghitung durasi penggunaan dalam jam
    private function hitungDurasi($waktuMulai, $waktuSelesai)
    {
        $start = Carbon::createFromFormat('H:i', $waktuMulai);
        $end = Carbon::createFromFormat('H:i', $waktuSelesai);

        return $end->diffInHours($start);
    }


// Menyimpan fasilitas baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'deskripsi' => 'string|max:255',
            'tipe' => 'string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga_sewa' => 'required|integer|min:1',
            'foto' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048', // validasi file gambar
        ]);

        // Simpan file foto ke storage/public/fasilitas
        $path = $request->file('foto')->store('fasilitas', 'public');

        Fasilitas::create([
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'lokasi' => $request->lokasi,
            'harga_sewa' => $request->harga_sewa,
            'foto' => $path, // simpan path foto
        ]);

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }
// Menyimpan data penggunaan fasilitas
    public function store_gunakan(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'harga_sewa' => $request->is_event ? 'nullable' : 'required|numeric|min:0',
            'is_event' => 'nullable|boolean',
        ]);

        // Ambil data fasilitas
        $fasilitas = Fasilitas::findOrFail($request->fasilitas_id);

        // Hitung durasi
        $mulai = strtotime($request->waktu_mulai);
        $selesai = strtotime($request->waktu_selesai);

        if ($selesai <= $mulai) {
            return back()->withErrors(['waktu_selesai' => 'Waktu selesai harus lebih dari waktu mulai']);
        }

        // Tentukan status dan total harga
        $isEvent = $request->has('is_event');
        $status = $isEvent ? 'event' : 'menunggu';
        $durasi = ($selesai - $mulai) / 3600; // dalam jam
        $hargaPerJam = Fasilitas::find($request->fasilitas_id)->harga_sewa;
        $totalBayar = $request->has('is_event') ? 0 : ($durasi * $hargaPerJam);


        // Simpan data ke database
        Checkout::create([
            // 'user_id' => auth()->id(), // Atau $request->user_id jika memang dikirim dari form
            'user_id' => $request->user_id, // bisa null
            'fasilitas_id' => $request->fasilitas_id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->waktu_mulai,
            'jam_selesai' => $request->waktu_selesai,
            'total_bayar' => $totalBayar,
            'status' => $status,
        ]);

        return redirect()->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas telah berhasil digunakan.');
    }


// Menampilkan form edit
    public function edit($id)
    {
        $data = Fasilitas::withTrashed()->findOrFail($id);
        return view('Admin.Fasilitas.edit', compact('data'));
    }
// Menampilkan form 'gunakan'
    public function gunakan($id)
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);
        $users = User::all();
        return view('Admin.Fasilitas.gunakan', compact('fasilitas','users'));
    }


// Memperbarui data fasilitas
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga_sewa' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);

        $data = $request->only(['nama_fasilitas', 'deskripsi', 'tipe', 'lokasi', 'harga_sewa']);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($fasilitas->foto && Storage::disk('public')->exists($fasilitas->foto)) {
                Storage::disk('public')->delete($fasilitas->foto);
            }

            // Simpan foto baru
            $data['foto'] = $request->file('foto')->store('fasilitas', 'public');
        }

        $fasilitas->update($data);

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }


// Soft delete
    public function delete($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil dihapus.');
    }


// Restore soft-deleted fasilitas
    public function restore($id)
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);
        $fasilitas->restore();

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil dipulihkan.');
    }


}
