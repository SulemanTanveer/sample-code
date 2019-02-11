<?php

namespace App\Http\Middleware;

use Closure;
use Lang;
use App\User;

class RedirectIfEmailNotConfirmed
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
        if ($request->email) {
            if (User::checkStatus($request->email)) {
               return response()->json([
                    'success' => false,
                    'message' => Lang::get('messages.check_account')
                ], 400);
            }
        }
        return $next($request);
    }
}
