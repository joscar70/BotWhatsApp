<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MensajesWhatsApp;
use DB;

class EnviarMensajeCtr extends Controller
{
    var $APIurl = 'https://api.chat-api.com/instance376180/';
    var $token = 'tn951zdx1rs716kr';

    public function sendMensaje(Request $request){
        //echo '<pre>'; print_r($request->all()); echo '</pre>';
        //die();
        
        if ($request->codPais == '') {
            return response()->json(['resultado' => 0 , "data" => ["error" => 'Es necesario el codigo del país']]);
        }
        
        if ($request->telefono == '') {
            return response()->json(['resultado' => 0 , "data" => ["error" => 'Numero de telefono no puede ser vacío']]);
        }
        
        if ($request->idEmpresa == '') {
            return response()->json(['resultado' => 0 , "data" => ["error" => 'Id de la empresa no puede ser vacío']]);
        }
        
        if ($request->mensaje == '') {
            return response()->json(['resultado' => 0 , "data" => ["error" => 'No existe mensaje para enviar']]);
        }
        
        $query = DB::select("SELECT idCliente FROM clientes WHERE clientes.idEmpresa = ?",[$request->idEmpresa]);
        if (count($query) == 0) {
            return CustomLibCtr::formatResultado(0 , ["error" => 'Cliente no Registrado, Por favor Comuniquese con el Administrador']);
        }

        
       
        if ($this->getCheckPhone($request->codPais.$request->telefono)) {

            $idCliente = DB::select("SELECT idTelefono, idCliente FROM telefonos WHERE telefono = ? AND estadoTelefono = 'ACTIVO'",[trim($request->codPais).trim($request->telefono), $query[0]->idCliente]);
            if (count($idCliente) > 0 && $idCliente[0]->idCliente != $query[0]->idCliente) {
                DB::select("UPDATE telefonos a SET a.estadoTelefono = 'INACTIVO' WHERE a.idTelefono = ?", [$idCliente[0]->idTelefono]);
            }
            $idTelefono = DB::select("SELECT idTelefono FROM telefonos WHERE telefono = ? and idCliente = ? ",[trim($request->codPais).trim($request->telefono), $query[0]->idCliente]);
            if (count($idTelefono) == 0) {
                DB::select("INSERT INTO telefonos (idCliente, telefono) VALUES (?,?)",[$query[0]->idCliente, trim($request->codPais).trim($request->telefono)]);
                $idTelefono = DB::select("SELECT idTelefono FROM telefonos WHERE telefono = ? and idCliente = ?",[trim($request->codPais).trim($request->telefono), $query[0]->idCliente]);
            }else{
                 DB::select("UPDATE telefonos a SET a.estadoTelefono = 'ACTIVO' WHERE a.idTelefono = ?", [$idTelefono[0]->idTelefono]);
            }
            $data = array('phone'=>trim($request->codPais).trim($request->telefono), 'body'=> $request->mensaje);
        
            $response = json_decode($this->sendRequest('message',$data));
        
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
        }else{
            return CustomLibCtr::formatResultado(0 ,'El número de Telefono no es válido');
        }
        
        
    }
    
    public function sendRequest($method,$data){
        $url = $this->APIurl.$method.'?token='.$this->token;

        if(is_array($data)){ $data = json_encode($data);}
        $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $data]]);

       
        $response = file_get_contents($url,false,$options);

        //file_put_contents('requests.log',$response.PHP_EOL,FILE_APPEND);
        return $response;
    }

    public function getCheckPhone($telefono = null){
        $url = $this->APIurl.'checkPhone'.'?token='.$this->token.'&phone='.$telefono;
         $options = stream_context_create(['http' => [
            'method'  => 'GET',
            'header'  => 'Content-type: application/json',
            'content' => '']]);
        $response = json_decode(file_get_contents($url,false,$options));
        if ((isset($response->result) &&  $response->result == "not exists") || isset($response->error)) {
            return false;
        }
        return true;

    }
}
