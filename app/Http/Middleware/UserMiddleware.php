<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
     public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        // Autoriser les utilisateurs ET les admins
        if (in_array(auth()->user()->role, ['user', 'admin'])) {
            return $next($request);
        }

        abort(403, 'غير مسموح لك بالدخول');
    }
}
