<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('pembeli')->check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = Auth::guard('pembeli')->user();
        
        if (!$user->isAdmin()) {
            return redirect()->route('index.user')->with('error', 'You do not have admin access.');
        }

        return $next($request);
    }
}