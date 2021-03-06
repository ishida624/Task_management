<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Users;

class LogsInfo
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
        // dd($request->path());
        $data = Users::where('remember_token', $token)->first();
        $method = $request->method();
        $ip = $request->ip();
        $path = $request->path();
        Log::info('request body', $request->all());
        if (isset($data->username)) {
            $username = $data->username;
            Log::info('request ', ['method' => "$method", 'path' => $path, 'ip' => "$ip", 'username' => "$username"]);
            return $next($request);
        } else {
            Log::info('request ', ['method' => "$method", 'path' => $path, 'ip' => "$ip",]);
            return $next($request);
        }
    }
}
