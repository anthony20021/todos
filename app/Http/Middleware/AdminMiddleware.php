<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next, $permission = null)
    {
        $user = Auth::user();

        // Vérifiez si l'utilisateur est authentifié et s'il a la permission requise
        if (!$user || !$user->hasPermission($permission)) {
            // Redirige ou retourne une réponse 403 (Accès interdit)
            abort(403, 'Vous n\'avez pas les autorisations nécessaires pour accéder à cette ressource.');
        }

        // Si l'utilisateur a la permission, continuer la requête
        return $next($request);
    }
}
