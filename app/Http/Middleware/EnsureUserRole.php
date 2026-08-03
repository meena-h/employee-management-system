<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!$request->user() || !$request->user()->is_active) {
            abort(403, 'Account inactive.');
        }
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'You do not have permission to perform this action.');
        }
        return $next($request);
    }
}
