<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{

    public function registrar(Request $request){

        $respuesta = "";

        $datos = $request->getContent();
        $datos = json_decode($datos);

        if($datos){
            
            $usuario = new Usuario();

            $usuario->nombre = $datos->nombre;
            $usuario->email = $datos->email;
            $usuario->pass = Hash::make($datos->pass);
            $usuario->rol = $datos->rol;

            try{
                $usuario->save();

                $respuesta = "OK";
            }catch(\Exception $e){
                $respuesta = $e->getMessage();
            }

        }else{
            $respuesta = "Datos incorrectos";
        }

        return response($respuesta);
    }
    
    public function logear(Request $request){

        $respuesta = "";
        
        $user = Usuario::whereEmail($request->email)->first();

        if($user && Hash::check($request->pass, $user->pass)){

            $token = $user->createToken('Cardmarket')->accessToken;

            $respuesta = "OK " . $token;

        }else{
            $respuesta = "Datos incorrectos";
        }

        return response($respuesta);
    }

    public function restablecerPass(Request $request){

        $respuesta = "";

        $email = json_decode($request->getContent());
        $user = Usuario::whereEmail($email)->first();

        if ($user) {

            $newPass = uniqid();
            $user->pass = Hash::make($newPass);
            $user->save();

            $respuesta = "Nueva contraseña: " . $newPass;
        }else{
            $respuesta = "Datos incorrectos";
        }

        return response($respuesta);
    }
}
