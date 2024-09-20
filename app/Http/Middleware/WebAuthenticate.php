<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            // Utilisation de la méthode redirectTo pour rediriger correctement
            $redirectPath = $this->redirectTo($request);

            // Si une redirection est nécessaire, on la renvoie
            if ($redirectPath) {
                return redirect($redirectPath);
            }

            // Si c'est une requête JSON (API), on retourne une réponse 401
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // L'utilisateur est authentifié, on continue avec la requête
        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return !$request->expectsJson() ? route('login') : null;
    }
}
