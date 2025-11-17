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
            $user = Auth::user();
            if (method_exists($user, 'updateLastLogin')) {
                $user->updateLastLogin();
            } else {
                DB::table('users')
                    ->where('id', Auth::id())
                    ->update(['last_login_at' => now()]);
            }
        }

        return $next($request);
    }
}
