<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in AND is an admin
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // Send regular users back to the shop home with a warning notice
        return redirect('/dashboard?tab=home')->with('error', 'Unauthorized access.');
    }
}
