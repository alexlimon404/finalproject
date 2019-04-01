<?php

namespace App\Http\Middleware;

use App\Enums\UserType;
use Closure;

class AllTypeUsers
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
        if ($request->user()->role == UserType::Admin or
            $request->user()->role == UserType::StoreUser or
            $request->user()->role == UserType::Customer) {
            return $next($request);
        }
    }
}
