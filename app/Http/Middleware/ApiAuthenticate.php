<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

class ApiAuthenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->cookies->get("accessToken");
        if ($accessToken) {
            $token = PersonalAccessToken::findToken($accessToken);
            if ($token) {
                $user = $token->tokenable;
                auth()->login($user);
                return $next($request); // 继续处理请求
            }
        }
        return response()->json(['message' => '没有访问权限'], 401);
    }
}
