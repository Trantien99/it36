<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        if (!$request->user()) {
            request()->session()->flash('error', 'Vui lòng đăng nhập bằng tài khoản quản trị để truy cập trang này');
            return redirect()->route('login.form');
        }

        if($request->user()->role=='admin'){
            return $next($request);
        }
        else{
            request()->session()->flash('error','Bạn không có quyền truy cập trang quản trị');
            return redirect()->route($request->user()->role === 'user' ? 'user' : 'home');
        }
    }
}
