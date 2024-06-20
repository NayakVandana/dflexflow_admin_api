<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Models\UserToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyToken extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->bearerToken() ?? $request->get('Authorization');

        if (!$token) {
            return $this->sendJsonResponse(false, 'Unauthorized');
        }

        $userToken = UserToken::where(function ($q) use ($token) {
            $q->where('web_access_token', $token);
            $q->orWhere('app_access_token', $token);
        })->first();

        if(!$userToken)    {
            return $this->sendJsonResponse(false, 'Unauthorized');
        }

        Auth::login($userToken->user);

        return $next($request);
    }
}