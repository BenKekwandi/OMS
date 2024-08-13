<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatusAndIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {

            if ($user->active === 0) {
                return response()->json([
                    'error' => 'Your account is deactivated.',
                ], 403);
            }

            if ($user->blocked_ips()->where('ip_address', $request->ip())->exists()) {
                return response()->json([
                    'error' => 'Your IP address is blocked.',
                ], 403);
            }
        }

        return $next($request);
    }
}
