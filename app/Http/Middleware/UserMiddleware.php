<?php

namespace App\Http\Middleware;

use Closure;

class UserMiddleware
{
    public function handle($request, Closure $next)
    {
        //读取SESSON值作为判断
        $user = session('loginInfo');
//        dd($user);
        //判断用户数据是否为空
        if ($user) {
            return $next($request);
        } else {
            return Redirect('/login');
        }
    }
}
