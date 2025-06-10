<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login.form');
        }

        if (Auth::user()->rol !== $role) {
            abort(403, 'No tienes permisos de administrador');
        }

        return $next($request);
    }
}