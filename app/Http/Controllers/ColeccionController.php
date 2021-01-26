<?php

namespace App\Http\Controllers;

use App\Models\Coleccion;
use App\Models\Carta;
use App\Models\RelacionColeccionCarta;
use Illuminate\Http\Request;

class ColeccionController extends Controller
{

    public function alta(Request $request){
        $respuesta = "";
        $chechColeccion = false;

        $datos = $request->getContent();
        $datos = json_decode($datos);

        $colecciones = Coleccion::all();
        $coleccion = new Coleccion();

        $cartas = Carta::all();
        $carta = new Carta();

        $relacion = new RelacionColeccionCarta();

        if($datos){
            foreach($colecciones as $coleccionAlmacenada){
                if($datos->nombre == $coleccionAlmacenada->nombre){
                    $chechColeccion = true;
                }
            }

            if($chechColeccion){
                $respuesta = "Colección ya existente";
            }else{
                $coleccion->nombre = $datos->nombre;

                foreach ($cartas as $cartaAlmacenada) {
                    if ($datos->nombreCarta == $cartaAlmacenada->nombre) {
                        $cartaAlmacenadaID = $cartaAlmacenada->id;
                    }
                }

                try{
                    $coleccion->save();

                    if(isset($cartaAlmacenadaID)){
                        $relacion->carta_id = $cartaAlmacenadaID;
                        $respuesta = "Colección creada";
                    }else{
                        $carta->nombre = $datos->nombreCarta;
                        $carta->descripcion = $datos->descripcionCarta;
                        $carta->save();
                        $relacion->carta_id = $carta->id;
                        $respuesta = "Colección y carta creadas";
                    }
                    $relacion->coleccion_id = $coleccion->id;
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

}
