<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\AdminResetPasswordNotification;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Anda bukan admin.');
    }
    public function sendPasswordResetNotification($token)
{
    $this->notify(new AdminResetPasswordNotification($token));
}
}
