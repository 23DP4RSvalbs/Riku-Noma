<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        $roleName = match (strtolower($role)) {
            'admin', 'administrator' => 'Administrators',
            'client', 'user', 'klients' => 'Klients',
            default => $role,
        };

        if (! $user || ! $user->lomas()->where('nosaukums', $roleName)->exists()) {
            return response()->json([
                'message' => 'Jums nav nepieciešamo tiesību.',
            ], 403);
        }

        return $next($request);
    }
}
