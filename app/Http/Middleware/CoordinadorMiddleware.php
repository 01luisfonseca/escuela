<?php

namespace App\Http\Middleware;

use Closure;

class CoordinadorMiddleware
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
        if(auth()->user()->tipo_usuario_id>=5){
            return $next($request);
        }else{
            return response('',404);
        }
    }
}
