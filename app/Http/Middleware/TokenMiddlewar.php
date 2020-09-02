<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Admin;
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
        $data = Admin::where('remember_token', $token)->first();
        $request->merge(['UserData' => $data]);
        if (isset($data->remember_token)) {
            $tokenTime =  strtotime('+1 day', strtotime($data->login_time));
            if ($tokenTime < time()) {
                return response()->json(['message' => 'Unauthorized', 'reason' => 'token out time'], 401);
            }
            return $next($request);
        } else {
            return response()->json(['message' => 'Unauthorized', 'reason' => 'token false'], 401);
        }
        // foreach ($data as $value) {
        //     // echo "$value";
        //     if ($value->remember_token == $cookie) {
        //         // echo "Hello";
        //         return $next($request);
        //     } else {
        //         $tokenAuth = 0;
        //     }
        // }
        // if ($tokenAuth == 0) {
        //     return redirect('LoginPage');
        // }
    }
}
