<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    // Menyimpan fasilitas baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'deskripsi' => 'string|max:255',
            'tipe' => 'string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga' => 'required|integer|min:1',
            'foto' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048', // validasi file gambar
        ]);

        // Simpan file foto ke storage/public/fasilitas
        $path = $request->file('foto')->store('fasilitas', 'public');

        Fasilitas::create([
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'lokasi' => $request->lokasi,
            'harga' => $request->harga,
            'foto' => $path, // simpan path foto
        ]);

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $data = Fasilitas::withTrashed()->findOrFail($id);
        return view('Admin.Fasilitas.edit', compact('data'));
    }

    // Memperbarui data fasilitas
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);

        $data = $request->only(['nama_fasilitas', 'deskripsi', 'tipe', 'lokasi', 'harga']);

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
