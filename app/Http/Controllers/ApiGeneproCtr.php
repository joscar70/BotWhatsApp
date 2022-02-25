<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiGeneproCtr extends Controller
{
    public static function senConfirmarAnular(Request $request){

        $datoJson = json_encode(array(
            "text"    => $request->mensaje ,
            "chatId"  => $request->idChat,
            "estado"  => $request->estado ,
        ));
       
       
        $token = UsuariosCtr::getToken($request);
        if (isset($token->resultado)) {
            return $token->data;
        }
        $confiAuth = array(
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
        );
       json_decode(SendRespuestaCtr::sendRespustaApiChat($request, $datoJson, $confiAuth));
        return;
    }


    
}
