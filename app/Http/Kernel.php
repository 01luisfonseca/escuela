<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'administrador' => \App\Http\Middleware\AdminMiddleware::class,
        'alumno' => \App\Http\Middleware\AlumnoMiddleware::class,
        'coordinador' => \App\Http\Middleware\CoordinadorMiddleware::class,
        'profesor' => \App\Http\Middleware\ProfesorMiddleware::class,
        'trabajador' => \App\Http\Middleware\TrabajadorMiddleware::class,
        'checkSerial' => \App\Http\Middleware\CheckSerial::class,

    ];
}
