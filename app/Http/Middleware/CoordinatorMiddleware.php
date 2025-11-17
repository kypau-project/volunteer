<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CoordinatorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('CoordinatorMiddleware: Handling request', [
            'is_authenticated' => Auth::check(),
            'user_id' => Auth::check() ? Auth::id() : null,
            'user_role' => Auth::check() ? Auth::user()->role : null
        ]);

        if (!Auth::check() || Auth::user()->role !== 'coordinator') {
            Log::warning('CoordinatorMiddleware: Access denied', [
                'user_role' => Auth::check() ? Auth::user()->role : null
            ]);
            abort(403, 'Unauthorized action.');
        }

        Log::info('CoordinatorMiddleware: Access granted');
        return $next($request);
    }
}
