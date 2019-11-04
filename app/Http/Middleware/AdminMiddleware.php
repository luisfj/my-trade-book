<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
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
        if($request->user() && $request->user()->is_admin())
            return $next($request);
        return response('Não possui autorização para esta area <a href="'.route('home').'">HOME</a>', 403);
    }
}
