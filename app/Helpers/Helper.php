<?php

namespace App\Helpers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\conf_colacorreos;
use App\Jobs\SendEmails;
use Config;
use DB;

class Helper{

    public static function enviarEmail($datos_enviar, $tipo_plantilla, $motivo, $codUsuarioRegistro ,$correoEnvio)
    {

        $plantilla  = "";
        if($tipo_plantilla == 'invitacion'){
            $plantilla =  'emails.invitation';
        }elseif ($tipo_plantilla == 'alerta') {
            $plantilla =  'emails.notificacion';
        }elseif ($tipo_plantilla == 'recuperar') {
            $plantilla =  'emails.forgetPassword';
        }elseif ($tipo_plantilla == 'retrasos') {
            $plantilla =  'emails.alertaretraso';
        }


        $conf_colacorreos                     = new conf_colacorreos;
        $conf_colacorreos->desMensaje         = view($plantilla , $datos_enviar)->render();
        $conf_colacorreos->dayFechaRegistro   = date('Y-m-d H:i:s');
        $conf_colacorreos->desMotivo          = $motivo;
        // "dayFechaEnvio" => date('Y-m-d H:i:s'),
        $conf_colacorreos->codUsuarioRegistro = $codUsuarioRegistro;
        $conf_colacorreos->desCorreoEnvio     = $correoEnvio;
        if($conf_colacorreos->save()) $mail   = true;

    }


    public static function callNotification($titulo , $mensaje, $proyecto){

        $to = '/topics/'.$proyecto;
        $title = $titulo;
        $description = $mensaje;

        $notif = array(
    		'title'=> $title,
    		'body'=> $description
    	);

    	// $this->sendNotification($to, $notif);
        self::sendNotification($to, $notif);
    }

    public static function sendNotification($to, $notif){
    	$apiKey = "AAAAhrfhpoM:APA91bEuBOW89E612QogeoO9uj7PhALX721aQyFmiRpOhHmH2kZJei1abgio1ZVk13BZUl4V4kXr3PO9FPFyfARTw21364DgtYX_0V-j_5Ztdjb2rcGlhiBVAEYz4NyvrgCbMsOtFCei";

    	$ch = curl_init();

    	$url = "https://fcm.googleapis.com/fcm/send";

    	$fields = json_encode(array('to'=>$to, 'notification'=>$notif));

    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    	$header = array();
    	$header[] = 'Authorization: key ='.$apiKey;
    	$header[] = 'Content-Type: application/json';
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    	$result = curl_exec($ch);
    	if (curl_errno($ch)) {
    		echo 'Error:' . curl_error($ch);
    	}
    	curl_close($ch);

    }

    public static function provamos (){
        echo ">>> eentrando";
    }

}
