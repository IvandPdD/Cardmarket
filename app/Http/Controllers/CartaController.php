<?php

namespace App\Http\Controllers;

use App\Models\Carta;
use App\Models\Coleccion;
use App\Models\RelacionColeccionCarta;
use App\Models\Venta;
use Illuminate\Http\Request;

class CartaController extends Controller
{
    public function alta(Request $request){

        $respuesta = "";
        $checkCarta = false;

        $datos = $request->getContent();
        $datos = json_decode($datos);

        $cartas = Carta::all();
        $carta = new Carta();

        $colecciones = Coleccion::all();
        $coleccion = new Coleccion();


        $relacion = new RelacionColeccionCarta();

        if($datos){
            foreach($cartas as $cartaAlmacenada){
                if($datos->nombre == $cartaAlmacenada->nombre){
                    $checkCarta = true;
                }
            }

            if($checkCarta){
                $respuesta = "Carta ya existente";
            }else{
                $carta->nombre = $datos->nombre;
                $carta->descripcion = $datos->descripcion;

                foreach ($colecciones as $coleccionAlmacenada) {
                    if ($datos->nombreColeccion == $coleccionAlmacenada->nombre) {
                        $coleccionAlmacenadaID = $coleccionAlmacenada->id;
                    }
                }

                try{
                    $carta->save();

                    if(isset($coleccionAlmacenadaID)){
                        $relacion->coleccion_id = $coleccionAlmacenadaID;
                        $respuesta = "Carta creada";
                    }else{
                        $coleccion->nombre = $datos->nombreColeccion;
                        $coleccion->save();
                        $relacion->coleccion_id = $coleccion->id;
                        $respuesta = "Colección y carta creadas";
                    }
                    $relacion->carta_id = $carta->id;
                    $relacion->save();
                }catch(\Exception $e){
                    $respuesta = $e->getMessage();
                }
            }
        }else{
            $respuesta = "Datos incorrectos";
        }
        return response($respuesta);
    }

    public function buscar(Request $request){

       $respuesta = "";
       $datos = $request->getContent();
       $datos = json_decode($datos);

       $cartas = Carta::where('nombre', 'like', '%' . $datos->nombre . '%')->get();

        if($cartas){

           $datosCartas = [];

            foreach ($cartas as $key=>$carta) {

                foreach ($carta->venta as $venta) {
                    $precio[$venta->carta_id] = $venta->precio;
                    $cantidad[$venta->carta_id] = $venta->cantidad;
                } 

                foreach ($carta->relacion as $relacion) {
                    $coleccion[] = $relacion->coleccion->nombre;
                }   

                $datosCartas[] = [
                    "nombre" => $carta->nombre,
                    "descripcion" => $carta->descripcion,
                    "coleccion" => $coleccion[$key],
                    "precio" =>  $precio[$carta->id] ?? "No a la venta",
                    "cantidad" =>  $cantidad[$carta->id] ?? 0
                ];
            }return response()->json($datosCartas);

        }return response("Carta no encontrada");
    }
}
