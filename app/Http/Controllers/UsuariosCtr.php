<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator,Redirect,Response,File;
use DB;
//use Exception;

class UsuariosCtr extends Controller
{
    public function login(Request $request)
    {
        try {
            $tokenExiste = DB::select("SELECT token.expiredDate FROM token WHERE token.usuario = ? AND token.expiredDate > CURRENT_TIMESTAMP() AND ISNULL(sesion)", [$request->username]);
            if (count($tokenExiste) > 0) {
                return CustomLibCtr::formatResultado(0 ,["error" => "!Error¡ Este usuario ya tiene token válido"]);
            }
            if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){ 
                $user = Auth::user(); 
                //echo '<pre>'; print_r($user); echo '</pre>';
                $tokenResult = $user->createToken('Api ApiWhatsApp');
                $token = $tokenResult->token;
                $token->expires_at = Carbon::now()->addDays(1);
                $token->save();
                DB::select("INSERT INTO token (token, expiredDate, usuario) VALUES (?, ?, ?)",[$tokenResult->accessToken, date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")."+ 1 days")), $request->username]);
                return CustomLibCtr::formatResultado(1 , ['access_token' => $tokenResult->accessToken, "fechaExp" => date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")."+ 1 days"))]);
            } 
            else{ 
                return CustomLibCtr::formatResultado(0 , ["error" => 'Ingreso no autorizado']);
            }    
        } catch (Exception $e) {
            return CustomLibCtr::formatResultado(0 ,["error" => "!Error¡ ".$e->getMessage()]);
        }
         
    }


    public function logout(Request $request)
    {
        $tokenRecibido = $request->header();
        $tokenRecibido = str_replace('Bearer ','',$tokenRecibido["authorization"][0]);
        $request->user()->token()->revoke();
        DB::select("UPDATE token SET sesion='CERRADA' WHERE token = ?",[$tokenRecibido]);
        return response()->json(["resultado" => 1,
            'data' => '¡Sesión cerrada satisfactoriamente!'
        ]);
    }

    public static function getToken(Request $request){
        $sql = "SELECT a.tokeRecibido FROM tokenrecibidos a WHERE a.fechaExp > CURRENT_TIMESTAMP() and a.url ='".$request->url."'";
       $token = DB::select($sql);
        if (count($token) == 0) {
            $datoJson = json_encode(array(
                "username" => "botwhatsapp",
                "password" => "B0tWhatsApp",
                "nombreAip" => "ApiWhatsApp"
                )
            );
            
            $confiAuth = array('Content-Type: application/json');
            $response = json_decode(CustomLibCtr::doCurl($request->url.'/api/auth/getAutenticacion', 'GET', $datoJson, $confiAuth));
            echo '<pre>'; print_r($response); echo '</pre>';
            
            if ($response->resultado == 1) {
                DB::select("INSERT INTO tokenrecibidos (tokeRecibido, fechaExp, url) VALUES (?, ?, ?)",[$response->data->access_token, $response->data->fechaExp, $request->url]);
                return $response->data->access_token;
                 
            }else{
                return $response;
            }
        }else{
            return $token[0]->tokeRecibido;
        }
        
    }
}
