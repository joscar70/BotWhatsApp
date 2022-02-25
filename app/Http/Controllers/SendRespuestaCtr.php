<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\MensajesWhatsApp;

class SendRespuestaCtr extends Controller
{
    public function sendRespustaApiChat(Request $request, $datoJson, $confiAuth){
        
       $response = json_decode(CustomLibCtr::doCurl($request->url.'/api/auth/confirmarAnular', 'POST', $datoJson, $confiAuth));
       echo '<pre>'; print_r($response); echo '</pre>';
       if ($response->resultado == 1) {
            $senMensaje = new EnviarMensajeCtr();
            $telefono = str_replace("@c.us", '',$response->data->chatId);
            $cliente = DB::select("SELECT idCliente FROM clientes WHERE clientes.idEmpresa = ?",[$response->data->idCliente]);
            $idTelefono = DB::select("SELECT idTelefono FROM telefonos WHERE telefono = ? and idCliente = ? ",[$telefono, $cliente[0]->idCliente]);
            $data = array('phone'=>$telefono, 'body'=> $response->data->mensaje);
            $response = json_decode($senMensaje->sendRequest('message',$data));
            if ($response->sent) {
                $MensajesWhatsApp = new MensajesWhatsApp();
                $MensajesWhatsApp->idTelefono = $idTelefono[0]->idTelefono;
                $MensajesWhatsApp->idMensaje  = $response->id;
                $MensajesWhatsApp->tipo       = "ENVIADO";
                $MensajesWhatsApp->chatId     = str_replace('Sent to ','',$response->message);
                $MensajesWhatsApp->save();
                return response()->json(["resultado" =>1, "data" => ["mensaje" => 'El mensaje fue eviado', "response" => $response]]);
            }else{
                return CustomLibCtr::formatResultado(1 ,'No se envió el mensaje');
            }
        }
    }
}