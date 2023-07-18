<?php

namespace App\Http\Middleware;

use Closure;

class CheckDeviceAuth
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
        if (is_null(session('playlist_id'))) {
            return redirect('device/login');
        }
        return $next($request);
    }
}
