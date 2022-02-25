<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomLibCtr extends Controller
{
    public static function doCurl($endPoin, $metodo, $datos, $confiAuth){
      
      $curl = curl_init();

          curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://'.$endPoin,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST =>  $metodo,
            CURLOPT_POSTFIELDS => $datos,
            CURLOPT_HTTPHEADER => $confiAuth
          ));

          $response = curl_exec($curl);
          curl_close($curl);
          return $response;
    }


    static public function formatResultado($resultado, $data, $log = false, $request = false){
      // if ($log) {
      //   LogCtr::postLog($data, $request);
      // }

      // if (is_object($data) || is_array($data)) {
      //   $data=json_encode($data);
        
      // }else{
      //   $data = '"'.$data.'"';
      // }
      return response()->json(["resultado" => $resultado, "data" => $data]);
    }
}
