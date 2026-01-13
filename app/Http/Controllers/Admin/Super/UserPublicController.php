<?php

namespace App\Http\Controllers\Admin\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserPublicController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('no_ktp', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(10);

        return view('Admin.Super.Masyarakat.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        // Load history transaksi user tersebut jika perlu
        // $transaksi = $user->checkouts()->latest()->get();
        return view('Admin.Super.Masyarakat.show', compact('user'));
    }

    // Fitur Lock Akun
    public function lock(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Toggle status (Membalikkan nilai 0 jadi 1, atau 1 jadi 0)
        // Kolom di DB Anda adalah 'is_locked'
        $user->is_locked = !$user->is_locked;
        $user->save();

        // Tentukan pesan berdasarkan status baru
        // Jika is_locked == true, berarti baru saja dibekukan
        $status = $user->is_locked ? 'dibekukan (Locked)' : 'diaktifkan (Unlocked)';
        $action = $user->is_locked ? 'LOCK' : 'UNLOCK';

        // Log Activity
        $this->recordLog($user, $action, "Mengubah status akun masyarakat menjadi $status.");

        return back()->with('success', "Akun berhasil $status.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;

        $user->delete();

        // Log Activity
        $this->recordLog($user, 'DELETE', "Menghapus akun masyarakat: $name");

        return back()->with('success', 'Data masyarakat berhasil dihapus.');
    }
}
