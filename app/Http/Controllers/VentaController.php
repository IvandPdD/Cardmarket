<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Usuario;
use App\Models\Carta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function venta(Request $request){
        $respueta = "";

        $datos = $request->getContent();
        $datos = json_decode($datos);

        $apiToken = $request->bearerToken();

        $venta = new Venta();

        $usuarios = Usuario::All();

        if ($datos){

            foreach ($usuarios as $usuario) {
                if ($usuario->api_token == $apiToken){
            
                    $userId = $usuario->id;   
                }
            }

            $venta->carta_id = $datos->carta_id;
            $venta->cantidad = $datos->cantidad;
            $venta->precio = $datos->precio;
            $venta->usuario_id = $userId;

            $carta = Carta::find($venta->carta_id);
            $carta->usuario_id = $userId;

            try{
                $venta->save();
                $carta->save();
                $respuesta = "En venta correcto."; 
            }catch(\Exception $e){
                $respuesta = $e->getMessage();
            }
        }else{
            $respuesta = "Datos incorrectos.";
        }
        return response($respuesta);
    }

}
