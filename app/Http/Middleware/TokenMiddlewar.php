<?php

namespace App\Http\Middleware;

use Closure;
use App\Users;
use Illuminate\Support\Facades\Auth;

class TokenMiddlewar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('userToken');
        $UserData = Users::where('remember_token', $token)->first();
        $request->merge(['UserData' => $UserData]);
        if (isset($UserData->remember_token)) {
            $tokenTime =  strtotime('+1 day', strtotime($UserData->login_time));
            if ($tokenTime < time()) {
                return response()->json(['message' => 'Unauthorized', 'reason' => 'token out time'], 401);
            }
            return $next($request);
        } else {
            return response()->json(['message' => 'Unauthorized', 'reason' => 'token false'], 401);
        }
    }
}
