<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class Supplier
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
        if (Auth::guard('web')->check()) {
            if (Auth::guard('web')->user()->IsSupplier()){
                return $next($request);
            }
        }
        return redirect()->back();
    }
}
