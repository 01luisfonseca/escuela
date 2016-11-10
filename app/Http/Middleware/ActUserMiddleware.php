<?php

namespace App\Http\Middleware;

use Closure;
use Log;

class ActUserMiddleware
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
        $user=auth()->user();
        if($user->tipo_usuario_id!=6){
            if($request->has('id')){
                Log::info('Usuario intenta hacer modificación.');
                $idreq=(int) $request->input('id');
                if($idreq==$user->id){
                    Log::info('Usuario hace modificación en si mismo.');
                    return $next($request);
                }else{
                    Log::info('Usuario id='.$user->id.' hace intento de modificación a usuario id='.$idreq);
                }
            }
            return back()->withFlashMessage('No se puede realizar la acción sobre otro usuario');
        }else{
            return $next($request);
        }
    }
}
