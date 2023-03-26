<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->level !== 1) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        return $next($request);
        // try {
        //     JWTAuth::parseToken()->authenticate();
        // } catch (\Exception$e) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // return $next($request);
        // if (Auth::check())
        // {
        //     $user = Auth::user();
        //     if ($user->level == 1)
        //         return $next($request);
        //     else
        //         return response()->json(['error' => 'Unauthorized'], 401);
        //     }
        // else
        // {
        //     return response()->json(['error' => 'Unauthorized!!'], 401);
        // }
        // try {
        //     $user = JWTAuth::parseToken()->authenticate();
        //     if ($user->level == 1) {
        //         return $next($request);
        //     } else {
        //         return response()->json(['error' => 'Unauthorized'], 401);
        //     }
        // } catch (Exception $e) {
        //     if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
        //         return response()->json(['error' => 'Token is invalid'], 401);
        //     }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
        //         return response()->json(['error' => 'Token is expired'], 401);
        //     }else{
        //         return response()->json(['error' => 'Authorization Token not found'], 401);
        //     }
        // }
    }
}
