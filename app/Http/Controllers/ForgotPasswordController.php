<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mail;
use Illuminate\Support\Str;
use Hash;
use DB;
use Config;
use Helper; // Important


class ForgotPasswordController extends Controller
{


    public function submitResetPasswordForm(Request $request)
      {

        $enviar            = array();
        $enviar["mensaje"] = "";
        $enviar['estado']  = false;

        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required'
            ]);

            $updatePassword = DB::table('password_resets')
            ->where([
              'email' => $request->email,
              'token' => $request->token
            ])
            ->first();

            $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
            DB::table('password_resets')->where(['email'=> $request->email])->delete();
            $enviar['mensaje'] = 'La contraseña se actualizo con exito!';
            $enviar['estado']  = true;

            if(!$updatePassword){
                $enviar['mensaje'] = 'Token Invalido !';
                return $enviar;
            }

        } catch (ValidationException $exception) {

            $enviar['mensaje'] = $exception->errors();
        }

        return $enviar;

      }


      public function validateTokenPassword(Request $request)
      {
        $enviar            = array();
        $enviar["mensaje"] = "";
        $enviar['estado']  = false;
        try {
            $updatePassword    = DB::table('password_resets')
                              ->where([
                                'email' => $request->email,
                                'token' => $request->token
                              ])
                              ->first();
            if($updatePassword){
                $enviar['estado'] = true;
            }

        } catch (\Throwable $e) {
            $enviar["mensaje"] = $e;
        }

        return $enviar;
      }

      public function submitForgetPasswordForm(Request $request)
      {


          $enviar =  array();
          $enviar["mensaje"] = "";
          $enviar["estado"]  = false;


          $request->validate([
              'email' => 'required|email|exists:users',
          ]);

          $token = Str::random(64);

          DB::table('password_resets')->insert([
              'email' => $request->email,
              'token' => $token,
              'created_at' => Carbon::now()
            ]);

          $datos_enviar = array();
        //   $datos_enviar['actividades']       = $actividades;
          $datos_enviar['des_correo']        = $request->email;
          $datos_enviar['des_proyecto']      = 'Testeamos';
          $datos_enviar['token']             = $token;
          $datos_enviar['des_link']          = Config::get('global.URL_CHANGE_CREDEN');
          $datos_enviar['des_direktor_icon'] = Config::get('global.ICON_DIREKTOR');

          Helper::enviarEmail($datos_enviar, 'recuperar', "Correo de Recuperacion de contraseña :  ".$request->email, 0 ,$request->email);

          $enviar["estado"]  = true;

        //     return "salimos con exito";
        //  print(">>> llegamos has aqiui");

        //   Mail::send('emails.forgetPassword', ['token' => $token], function($message) use($request){
        //       $message->to($request->email);
        //       $message->subject('Reset Password');
        //   });

          return $enviar;

        //   return back()->with('message', 'We have e-mailed your password reset link!');
      }

}
