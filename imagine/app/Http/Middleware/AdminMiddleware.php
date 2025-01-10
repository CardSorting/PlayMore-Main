<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !$user->hasRole('admin') || !$user->hasPermission('access_admin_panel')) {
            abort(403, 'Unauthorized access. Insufficient permissions.');
        }

        return $next($request);
    }
}
