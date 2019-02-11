<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class LastActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user->update([
                'last_active' => Carbon::now()
            ]);
        }
        return $next($request);
    }
}
