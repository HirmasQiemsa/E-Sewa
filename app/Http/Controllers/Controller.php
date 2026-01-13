<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    // Method Helper Cerdas
    public function recordLog($subjectModel, $action, $description)
    {
        $adminId = null;
        $userId  = null;

        // 1. Cek apakah yang login adalah ADMIN
        if (Auth::guard('admin')->check()) {
            $adminId = Auth::guard('admin')->id();
        }
        // 2. Cek apakah yang login adalah USER (Masyarakat)
        // Guard default user biasanya 'web', sesuaikan jika Anda pakai nama lain
        elseif (Auth::guard('web')->check()) {
            $userId = Auth::guard('web')->id();
        }

        // 3. Simpan ke Database
        ActivityLog::create([
            'admin_id'     => $adminId, // Akan terisi jika Admin login
            'user_id'      => $userId,  // Akan terisi jika User login
            'action'       => $action,
            'description'  => $description,
            'subject_type' => get_class($subjectModel),
            'subject_id'   => $subjectModel->id,
        ]);
    }
}
