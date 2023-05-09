<?php

namespace App\Helpers;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\Console\Input\Input;

class Helper {

   public static function sendError( $message, $errors = [], $code = 401 ){

        $response = ["success" => false, "message" => $message ];

        if( !empty($errors) ) {
            $response['data'] = $errors;
        }

        throw new HttpResponseException(
            response()->json( $response, $code )
        );
   }

   public static function sendErrorUpdate( $id, $request, $input ){

    if( !is_numeric($id) ){
        throw new HttpResponseException(
            response()->json([
            "success" => false,
            "message" => " El parametro ID debe ser numerico ",
            ],401)
        );
    }

    if ( count( $request->all() ) === 0  || !$input || count( $input ) === 0 ){
        throw new HttpResponseException(
            response()->json([
            "success" => false,
            "message" => "Parametros no validos",
            ],401)
        );
    }

    /*if( is_array( $input ) && count( $input ) > 0){

        $campoNotNull = false;

        foreach( $input as  $valor ){
            if( $valor !== null ){
                $campoNotNull = true;
                //break;
            }
            throw new HttpResponseException(
                response()->json([
                "success" => false,
                "valor" => $valor,
                "message" => "Parametros no validos",
                ],401)
            );
        }*/

        /*if( !$campoNotNull ){
            throw new HttpResponseException(
                response()->json([
                "success" => false,
                "message" => "Parametros no validos",
                ],401)
            );
        }*/


   }


   public static function sendErrorShow( $id ){

    if( !is_numeric($id) ){
        throw new HttpResponseException(
            response()->json([
            "success" => false,
            "message" => " El parametro ID debe ser numerico ",
            ],401)
        );
    }

   }

}
