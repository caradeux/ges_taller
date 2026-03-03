<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRolePermission
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return $next($request);
        }

        // Admins always pass
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Inactive users are blocked
        if (!$user->active) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Tu cuenta está desactivada.']);
        }

        $routeName = $request->route()?->getName();

        if (!$routeName) {
            return $next($request);
        }

        $permissions = config('permissions', []);

        foreach ($permissions as $pattern => $roles) {
            if ($this->matchPattern($pattern, $routeName)) {
                if (in_array($user->role, $roles)) {
                    return $next($request);
                }
                // Matched pattern but role not allowed
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'No autorizado.'], 403);
                }
                return redirect()->route('dashboard')
                    ->with('error', 'No tienes permiso para acceder a esa sección.');
            }
        }

        // No pattern matched: allow by default (auth middleware already checks login)
        return $next($request);
    }

    private function matchPattern(string $pattern, string $routeName): bool
    {
        if ($pattern === $routeName) {
            return true;
        }

        // Support wildcard * at end: 'quotations.*'
        if (str_ends_with($pattern, '*')) {
            $prefix = rtrim($pattern, '*');
            return str_starts_with($routeName, $prefix);
        }

        return false;
    }
}
