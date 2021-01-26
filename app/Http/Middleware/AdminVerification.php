<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Usuario;

class AdminVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $apiToken = $request->bearerToken();

        $user = Usuario::where('api_token', $apiToken)->first();

        if($user){
            if($user->rol != 'admin'){
                return response("Acceso denegado", 403);
            }
        }else{
            return response("Datos incorrectos", 401);
        }

        return $next($request);
    }
}
