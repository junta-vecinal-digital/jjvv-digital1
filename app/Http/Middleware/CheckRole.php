<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Verificar si el rol del usuario está dentro de los roles permitidos
        if ($user->role && in_array($user->role->nombre, $roles)) {
            return $next($request);
        }

        // Si es un vecino intentando acceder al panel de admin, redirigir a su portal
        if ($user->hasRole('vecino')) {
            return redirect()->route('portal.index')->with('error', 'No tienes permisos de directiva para acceder a esa sección.');
        }

        abort(403, 'Acceso no autorizado.');
    }
}