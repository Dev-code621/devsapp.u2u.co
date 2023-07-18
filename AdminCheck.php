<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminCheck
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->is_admin!=1) {
            return redirect('/sales');
        }
        return $next($request);
    }
}
