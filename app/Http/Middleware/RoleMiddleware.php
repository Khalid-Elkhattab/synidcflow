<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',

            ], 401);
        }

        $userRole = $user->role instanceof \BackedEnum
        ? $user->role->value
        : $user->role;

        if (! in_array($userRole, $roles, true)) {
            return response()->json([
                'success' => false,
                'message' => "Vous n'avez pas l'autorisation nécessaire.",
            ], 403);

        }

        return $next($request);
    }
}
