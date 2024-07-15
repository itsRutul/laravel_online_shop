<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 2) {  // Check for role 2 (admin)
            View::share('admin', Auth::user());
            return $next($request);
        }

        if (!$request->expectsJson()) {
            return redirect()->route('admin.login');
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
