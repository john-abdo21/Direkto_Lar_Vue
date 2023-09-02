<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Helper; // Important
use DB;

class NotificationController extends Controller
{


    public function callNotification(Request $request){

        $to = '/topics/Proyecto001';
        $title = $request->title;
        $description = $request->description;

        $notif = array(
    		'title'=> $title,
    		'body'=> $description,
            'sound'=>'notification1'

    	);

    	$this->sendNotification($to, $notif);
    }

    public function sendNotification($to, $notif){
    	$apiKey = "AAAAhrfhpoM:APA91bEuBOW89E612QogeoO9uj7PhALX721aQyFmiRpOhHmH2kZJei1abgio1ZVk13BZUl4V4kXr3PO9FPFyfARTw21364DgtYX_0V-j_5Ztdjb2rcGlhiBVAEYz4NyvrgCbMsOtFCei";

    	$ch = curl_init();

    	$url = "https://fcm.googleapis.com/fcm/send";

        $priority =  array("priority"=>"high");

        $headers  =  array("headers"=>array("apns-priority"=>"10"));
    	$fields = json_encode(array(
            'to'=>$to,
            'notification'=>$notif ,
            'android'=>array(
              'priority' => 'high'
            ),
            'apns' => array(
                'headers' => array(
                    'apns-priority' => '10'
                )
            )
        )
        );

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

}
