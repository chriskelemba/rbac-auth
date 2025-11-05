<?php

namespace RbacAuth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        $roles = collect($roles)
            ->flatMap(fn ($r) => preg_split('/[|,]/', $r))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (! $user || ! $user->hasAnyRole($roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        return $next($request);
    }
}
