<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (! $request->user()->hasRole($role)) {
            if ($request->wantsJson()) {
                return response('This action is unauthorized.', 401);
            }
            abort(401, 'This action is unauthorized.');
        }
        return $next($request);
    }
}
