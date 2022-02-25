<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ApiWhatsAppBotCtr extends Controller
{
    //specify instance URL and token
    var $APIurl = 'https://api.chat-api.com/instance376180/';
    var $token = 'tn951zdx1rs716kr';

    public function __construct(Request $request){
                        //get the JSON body from the instance
        $json = file_get_contents('php://input');
        $decoded = json_decode($json,true);
        file_put_contents('input_requestsJson.log',$json.PHP_EOL,FILE_APPEND);
                        //write parsed JSON-body to the file for debugging
        ob_start();
        var_dump($decoded);
        $input = ob_get_contents();
        ob_end_clean();
        file_put_contents('input_requests.log',$input.PHP_EOL,FILE_APPEND);

        if(isset($decoded['messages'])){
            
            foreach($decoded['messages'] as $message){

                //delete excess spaces and split the message on spaces. The first word in the message is a command, other words are parameters
                $text = explode(' ',trim($message['body']));
                //echo '<pre>'; print_r($text); echo '</pre>';
                if(!$message['fromMe']){
                    $request->request->add(['texto' => mb_strtoupper($text[0],'UTF-8'), "idChat" => $message['chatId'], 'message' => $message]);
                    $this->botResponse($request, $message, $decoded);
                    
                }
                
                //current message shouldn't be send from your bot, because it calls recursion
                // if(!$message['fromMe']){
                //     $request->request->add(['texto' => mb_strtoupper($text[0],'UTF-8'), "idChat" => $message['chatId'], 'message' => $message]);
                //     $this->botResponse($request)
                //     //check what command contains the first word and call the function
                //     switch(mb_strtolower($text[0],'UTF-8')){
                //         case 'hola':  {$this->welcome($message['chatId'],false); break;}
                //         case '1': {$this->showchatId($message['chatId']); break;}
                //         case '2':   {$this->time($message['chatId']); break;}
                //         case '3':     {$this->me($message['chatId'],$message['senderName']); break;}
                //         case '4':   {$this->file($message['chatId'],$text[1]); break;}
                //         case '5':     {$this->ptt($message['chatId']); break;}
                //         case '6':    {$this->geo($message['chatId']); break;}
                //         case '7':  {$this->group($message['author']); break;}
                //         case 'confirmar':  {$this->ConfirmarAnular($message['chatId'],'Su cita fue Confirmada. ¡Gracias por su Preferencia, lo Esperamos...!','CONFIRMADO', $message);
                //             break;}
                //         case 'anular':  {$this->ConfirmarAnular($message['chatId'],'Su cita fue Anulada','ANULADO',$message);
                //             break;}
                //         default:        {$this->welcome($message['chatId'],true); break;}
                //     }
                // }
                
            }
        }
        if (isset($decoded['ack'])) {
            foreach ($decoded['ack'] as $ack) {
                if ($ack["status"] == 'sent') {
                    $fecha = ", a.fechaEnvio ='".date('Y-m-d H:i:s')."'";
                }elseif ($ack["status"] == 'delivered') {
                    $fecha = ", a.fechaEntrega ='".date('Y-m-d H:i:s')."'";
                }else{
                    $fecha = ", a.fechaVisto ='".date('Y-m-d H:i:s')."'";
                }
                $sql = "UPDATE mensajes_whatsapp a SET a.datosMensajeApiChat = JSON_SET(a.datosMensajeApiChat, '$.registroMensaje.status', '".$ack["status"]."')".$fecha." WHERE a.idMensaje = '".$ack["id"]."'";
                file_put_contents('input_sql.log',$sql.PHP_EOL,FILE_APPEND);
                DB::select($sql);
                $query = DB::select("SELECT * FROM ack_whatsapp a WHERE JSON_EXTRACT(a.mensajeAck, '$.queueNumber') = ? AND JSON_EXTRACT(a.mensajeAck, '$.status') = ?",[$ack["queueNumber"],$ack["status"] ]);
                if (count($query) == 0) {
                    DB::select("INSERT INTO ack_whatsapp (idMensaje, mensajeAck) VALUES (?,?)",[$ack["id"], json_encode($ack)]);
                }
                
            }
            $telefono = str_replace("@c.us", '', $ack["chatId"]);
            $query   = DB::select("SELECT * FROM clientes a JOIN telefonos b ON a.idCliente = b.idCliente WHERE b.telefono = ? AND b.estadoTelefono = 'ACTIVO'",[$telefono]);
            $this->sendClienteResponse($decoded, $query[0]->url);

        }

        
        
        die();
    }

    public function botResponse(Request $request, $message, $decoded){
        $telefono = str_replace("@c.us", '', $request->idChat);
        $query   = DB::select("SELECT * FROM clientes a JOIN telefonos b ON a.idCliente = b.idCliente WHERE b.telefono = ? AND b.estadoTelefono = 'ACTIVO'",[$telefono]);
        $this->postMensaje($message, $query[0]->idTelefono, $decoded, $query[0]->url);
        if (count($query) == 0 ) {
            $welcomeString = "Número no registrado. Gracias!\n";
            $this->sendMessage($request->idChat,$welcomeString);
        }
        $clases = json_decode($query[0]->clases);
        $bienvenida = true;
        foreach ($clases->clases as $key => $value) {
            $flag = false;
            foreach ($value->palabra as $palabra) {
                if (str_contains(strtoupper($request->texto), $palabra)) {
                    $flag = true;
                }
            }
            if ($flag) {
                $clase  = "App\Http\Controllers\\".$value->clase;
                $metodo = $value->metodo;
                foreach ($value->parametros  as $key2 => $value2) {
                    $request->request->add([$key2 => $value2]);
                }
                $bienvenida = false;
                break;
            }
        }
        if (!$bienvenida) {
            $url = explode('/', $query[0]->url);
            $request->request->add(["url" => $url[0]]);
            $clase::$metodo($request);
        }else{
            $welcomeString = $query[0]->mensajeBot;
            $this->sendMessage($request->idChat,$welcomeString);
        }
        die();
    }

    //this function calls function sendRequest to send a simple message
    //@param $chatId [string] [required] - the ID of chat where we send a message
    //@param $text [string] [required] - text of the message
    public function welcome($chatId, $noWelcome = false){
        $welcomeString = "WORKMED. Salud y Protección Laboral\n";
        $this->sendMessage($chatId,
            $welcomeString
        );
    }

    //sends Id of the current chat. it is called when the bot gets the command "chatId"
    //@param $chatId [string] [required] - the ID of chat where we send a message
    public function showchatId($chatId){
        $this->sendMessage($chatId,'chatId: '.$chatId);
    }
        //sends current server time. it is called when the bot gets the command "time"
        //@param $chatId [string] [required] - the ID of chat where we send a message
    public function time($chatId){
        $this->sendMessage($chatId,date('d.m.Y H:i:s'));
    }
        //sends your nickname. it is called when the bot gets the command "me"
        //@param $chatId [string] [required] - the ID of chat where we send a message
        //@param $name [string] [required] - the "senderName" property of the message
    public function me($chatId,$name){
        $this->sendMessage($chatId,$name);
    }
        //sends a file. it is called when the bot gets the command "file"
        //@param $chatId [string] [required] - the ID of chat where we send a message
        //@param $format [string] [required] - file format, from the params in the message body (text[1], etc)
    public function file($chatId,$format){
        $availableFiles = array(
            'doc' => 'document.doc',
            'gif' => 'gifka.gif',
            'jpg' => 'jpgfile.jpg',
            'png' => 'pngfile.png',
            'pdf' => 'presentation.pdf',
            'mp4' => 'video.mp4',
            'mp3' => 'mp3file.mp3'
        );

        if(isset($availableFiles[$format])){
            $data = array(
                'chatId'=>$chatId,
                'body'=>'https://domain.com/PHP/'.$availableFiles[$format],
                'filename'=>$availableFiles[$format],
                'caption'=>'Get your file '.$availableFiles[$format]
            );
            $this->sendRequest('sendFile',$data);
        }
    }

    //sends a voice message. it is called when the bot gets the command "ptt"
    //@param $chatId [string] [required] - the ID of chat where we send a message
    public function ptt($chatId){
        $data = array(
            'audio'=>'https://domain.com/PHP/ptt.ogg',
            'chatId'=>$chatId
        );
        $this->sendRequest('sendAudio',$data);
    }

    //sends a location. it is called when the bot gets the command "geo"
    //@param $chatId [string] [required] - the ID of chat where we send a message
    public function geo($chatId){
        $data = array(
            'lat'=>51.51916,
            'lng'=>-0.139214,
            'address'=>'Ваш адрес',
            'chatId'=>$chatId
        );
        $this->sendRequest('sendLocation',$data);
    }

    //creates a group. it is called when the bot gets the command "group"
    //@param chatId [string] [required] - the ID of chat where we send a message
    //@param author [string] [required] - "author" property of the message
    public function group($author){
        $phone = str_replace('@c.us','',$author);
        $data = array(
            'groupName'=>'Group with the bot PHP',
            'phones'=>array($phone),
            'messageText'=>'It is your group. Enjoy'
        );
        $this->sendRequest('group',$data);
    }

    public function sendMessage($chatId, $text){
        $data = array('chatId'=>$chatId,'body'=>$text);
        $enviarMensaje = new EnviarMensajeCtr();
        $enviarMensaje->sendRequest('message',$data);
        die();
    }

    public function sendRequest($method,$data){
        $url = $this->APIurl.$method.'?token='.$this->token;
        if(is_array($data)){ $data = json_encode($data);}
        $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $data]]);
        $response = file_get_contents($url,false,$options);
        echo '<pre>'; print_r($response); echo '</pre>';
        file_put_contents('requests.log',$response.PHP_EOL,FILE_APPEND);
    }
    //execute the class when this file is requested by the instance
    //new whatsAppBot();

   

    public function postMensaje($mensaje, $idTelefono, $decoded, $url){

        $query = DB::select("SELECT * FROM mensajes_whatsapp a WHERE a.idMensaje = ?",[$mensaje["id"]]);

        if (count($query) > 0) {
            //$sql = "UPDATE mensajes_whatsapp a SET a.datosMensajeApiChat = '".json_encode($mensaje)."' WHERE a.idMensaje = '".$mensaje["id"]."'";
            //$update = DB::select($sql);
        }else{
            $tipo = (strpos($mensaje["id"],'false') === 0 ?'RECIBIDO':(strpos($mensaje["id"],'true') === 0?'ENVIADO':''));
            $update = DB::select("INSERT INTO mensajes_whatsapp (chatId, idTelefono,idMensaje,datosMensajeApiChat,tipo) VALUES(?,?,?,?,?)",[$mensaje["chatId"], $idTelefono,$mensaje["id"], json_encode($mensaje), $tipo]);
        }
        $this->sendClienteResponse($decoded, $url);
        return;

    }

    public function sendClienteResponse($decoded, $url){
        $datoJson = json_encode($decoded);
        $confiAuth = array(
            'Content-Type: application/json'
        );
        $response = CustomLibCtr::doCurl($url, 'POST', $datoJson, $confiAuth);
        echo '<pre>'; print_r($response); echo '</pre>';
        return;
    }
}