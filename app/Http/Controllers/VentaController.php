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
        $checkVenta = false;
        $checkCarta = false;

        $datos = $request->getContent();
        $datos = json_decode($datos);

        $apiToken = $request->bearerToken();

        $ventas = Venta::all();
        $venta = new Venta();

        $cartas = Carta::all();
        $usuarios = Usuario::All();

        if ($datos){

            foreach ($usuarios as $usuario) {
                if ($usuario->api_token == $apiToken){
            
                    $userId = $usuario->id;   
                }
            }

            foreach ($ventas as $test) {
                if ($test->carta_id == $datos->carta_id){
                    $checkVenta = true;
                }
            }

            if(Carta::find($datos->carta_id)){
                $checkCarta = false;
            }else{$checkCarta = true;}

            if($checkVenta){
                $respuesta = "Carta ya a la venta";
            }
            elseif($checkCarta){
                 $respuesta = "Carta no encontrada";
            }else{
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
            }
        }else{
            $respuesta = "Datos incorrectos.";
        }
        return response($respuesta);
    }

}
