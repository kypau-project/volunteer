<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            DB::table('users')
                ->where('id', Auth::id())
                ->update(['last_login' => now()]);
        }

        return $next($request);
    }
}
