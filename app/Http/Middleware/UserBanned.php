<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth('api')->check() && auth('api')->user()->status != 'banned'){
            return $next($request);
        }
        return response()->json([
            'status' => false,
            'message' => __('messages.error_middleware.user_banned')
        ],403);
    }
}
