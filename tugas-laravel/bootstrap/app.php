<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // KUNCI UTAMA: Bebaskan cookie preferensi dari enkripsi agar bisa dibaca JavaScript
        $middleware->encryptCookies(except: [
            'theme',
            'font_size',
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\CekAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
