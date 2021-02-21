<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Usuario;

class VentaVerification
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
            if($user->rol == 'admin'){
                return response("Función no disponible para rango Admin", 403);
            }

        }else{
            return response("Invalid token", 401);
        }
        return $next($request);
    }
}
