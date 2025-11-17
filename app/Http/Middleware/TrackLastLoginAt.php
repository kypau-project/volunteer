<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackLastLoginAt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the response
        $response = $next($request);

        // Check if user just logged in (auth attempt was successful)
        if (Auth::check() && $request->is('login') && $request->isMethod('post')) {
            $user = Auth::user();
            $user->updateLastLoginTimestamp();
        }

        return $response;
    }
}
