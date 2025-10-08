<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (Auth::check()) {
    //         $user = Auth::user();
    //         $userRoles = $user->roles->pluck('name')->toArray();

    //         if (!empty($userRoles)) {
    //             return $next($request);
    //         }

    //         abort(403, "User does not have the correct ROLE");
    //     }

    //     abort(401);
    // }
}
