<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Middleware global untuk web
        $this->app->router->middlewareGroup('web', [
            HandlePrecognitiveRequests::class,
            VerifyCsrfToken::class,
            // Tambahkan middleware lain di sini
        ]);

        // Middleware global untuk API
        $this->app->router->middlewareGroup('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // Tambahkan middleware API lain jika perlu
        ]);

        // ✅ foreign key constraint khusus untuk SQLite
        if (DB::getDriverName() === 'sqlite') {
        DB::statement('PRAGMA foreign_keys = ON;');
        }

        // Daftarkan middleware role
        // $this->app->router->aliasMiddleware('admin', AdminMiddleware::class);
        // $this->app->router->aliasMiddleware('user', UserMiddleware::class);
    }
}
