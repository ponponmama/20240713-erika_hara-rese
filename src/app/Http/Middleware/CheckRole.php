<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @param  string  $role
    * @return mixed
　  */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            // ユーザーがログインしていない、または必要な役割を持っていない場合
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}