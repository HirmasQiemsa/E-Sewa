<?php

namespace App\Http\Controllers\Admin\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = Admin::where('role', '!=', 'super_admin')->latest();

        if ($request->filled('search')) {
            // Ubah pencarian ke username
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(10);

        return view('Admin.Super.User.index', compact('users'));
    }

    public function create()
    {
        return view('Admin.Super.User.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            // Ganti validasi email menjadi username
            'username' => 'required|string|unique:admins,username|alpha_dash|min:4',
            'email'    => 'nullable|email', // Email jadi opsional
            'password' => 'required|min:6',
            'role'     => 'required|in:admin_fasilitas,admin_pembayaran',
            'no_hp'    => 'nullable|string|max:15',
        ]);

        try {
            $admin = Admin::create([
                'name'     => $request->name,
                'username' => $request->username, // Simpan username
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
                'no_hp'    => $request->no_hp,
                'is_active'=> true,
            ]);

            $this->recordLog($admin, 'CREATE', 'Menambahkan staff baru: ' . $admin->name . ' (' . $admin->role . ')');

            return redirect()->route('admin.super.users.index')->with('success', 'User staff berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $user = Admin::findOrFail($id);
        if($user->role == 'super_admin') abort(403);

        return view('Admin.Super.User.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'alpha_dash', Rule::unique('admins')->ignore($user->id)],
            'email'    => 'required|email',
            'role'     => 'required|in:admin_fasilitas,admin_pembayaran',
            'password' => 'nullable|min:6',
        ]);

        try {
            $data = [
                'name'      => $request->name,
                'username'  => $request->username, // Update username
                'email'     => $request->email,
                'role'      => $request->role,
                'no_hp'     => $request->no_hp,
                'is_active' => $request->is_active ?? 0,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            $this->recordLog($user, 'UPDATE', 'Mengubah data staff: ' . $user->name);

            return redirect()->route('admin.super.users.index')->with('success', 'Data staff diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = Admin::findOrFail($id);
        if($user->role == 'super_admin') return back()->with('error', 'Tidak bisa menghapus Super Admin.');

        $user->delete();

        $this->recordLog($user, 'DELETE', 'Menghapus staff: ' . $user->name);

        return back()->with('success', 'User staff dihapus.');
    }
}
