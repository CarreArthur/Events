<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFilamentAuthentication
{
    /**
     * Rediriger les utilisateurs non authentifiés vers le formulaire de login
     */
    public function handle(Request $request, Closure $next)
    {
        // Si l'utilisateur n'est pas authentifié, rediriger vers /login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Si l'utilisateur est un employee qui accède à /admin et n'est pas admin, rediriger vers dashboard
        if ($request->path() === 'admin' || strpos($request->path(), 'admin/') === 0) {
            if (!auth()->user()->isAdmin()) {
                // Les employees ne peuvent accéder qu'à leurs ressources (événements)
                if ($request->path() === 'admin') {
                    return redirect()->route('dashboard');
                }
            }
        }

        return $next($request);
    }
}
