<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Middleware Global
        // Laravel 11 biasanya sudah otomatis memuat StartSession dll.
        // Cek dulu apakah perlu di-append manual. Jika session jalan, hapus blok ini.

        $middleware->append([
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ]);


        // Middleware Alias (Panggilan Singkat di Route)
        $middleware->alias([
            // Role Checker Utama (Yang kita pakai di web.php)
            'role' => CheckRole::class,
        ]);
    })
    // KONFIGURASI HANDLING ERROR
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (TokenMismatchException $e, Request $request) {

            // Jika request adalah AJAX (seperti dari tombol delete/toggle status)
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Sesi Anda telah berakhir. Silakan refresh halaman dan login kembali.'
                ], 419);
            }

            // Jika request biasa (Submit Form)
            return redirect()->route('login')->with('error', 'Sesi Anda telah berakhir (Page Expired). Silakan login kembali untuk melanjutkan.');
        });

    })->create();
