<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
{
    // Jika user belum login atau role-nya tidak ada dalam daftar yang diizinkan
    if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
        return redirect('/')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
    }

    return $next($request);
}
}
