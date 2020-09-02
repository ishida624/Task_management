<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Admin;

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
        $data = Admin::where('remember_token', $token)->first();
        $method = $request->method();
        $ip = $request->ip();
        $path = $request->path();
        if (isset($data->admin)) {
            $username = $data->admin;
            Log::info('request ', ['method' => "$method" , 'path'=>$path , 'ip' => "$ip", 'username' => "$username" ]);
            return $next($request);
        } else {
            Log::info('request ', ['method' => "$method" , 'path'=>$path , 'ip' => "$ip", ]);
            return $next($request);
        }
    }
}
