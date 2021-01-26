<?php

namespace App\Http\Controllers;

use App\Models\Carta;
use App\Models\Coleccion;
use App\Models\RelacionColeccionCarta;
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

       $id = Carta::where('nombre', $datos->nombre)->value('id');
       $carta = Carta::find($id);

        if($carta){

           $cartas = [];

            foreach ($carta->relacion as $relacion) {
                $coleccion = $relacion->coleccion->nombre;
            }

            $cartas[] = [
                "nombre" => $carta->nombre,
                "descripcion" => $carta->descripcion,
                "coleccion" => $coleccion
            ];
            return response()->json($cartas);
        }

        return response("Carta no encontrada");
    }
}
