<?php

namespace App\Http\Middleware;

use Closure;
use App\Users;
use Carbon\Carbon;
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
        $userData = Users::where('remember_token', $token)->first();
        #將User資訊合併進去request，傳到後端
        $request->merge(['userData' => $userData]);
        if (isset($userData->remember_token)) {
            // $tokenTime =  strtotime('+1 day', strtotime($userData->updated_at));
            $tokenTime = $userData->updated_at->addDays(1);
            // dd(Carbon::now(), $tokenTime);
            #判斷token是否過期
            if ($tokenTime < Carbon::now()) {
                return response()->json(['status' => false, 'error' => 'token out time'], 401);
            }
            return $next($request);
        } else {
            return response()->json(['status' => false, 'error' => 'token false'], 401);
        }
    }
}
