<?php
/**
 * User: Zura
 * Date: 12/19/2021
 * Time: 3:49 PM
 */

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\Rules\Password;
use App\Models\ProjectUser;

use Helper; // Important
use DB;

/**
 * Class AuthController
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'celular'  => 'required|numeric',
            'nombreempresa' => 'required|string',
            'email' => 'required|email|string|unique:users,email',
            // 'cargo' => 'required|numeric',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ]
        ],
        [
            'name.required' => ' Campo nombre requerido! ',
            'lastname.required' => ' Campo apellido requerido ! ',
            'celular.required'  => ' Campo celular requerido ! ',
            'nombreempresa.required'  => ' Campo empresa requerido ! ',
            'email.required'  => ' Campo email con errores! ',
            'email.unique'  => ' Este Correo ya existe en el sistema ! ',
            // 'cargo.required'  => ' Campo cargo con errores! ',
            // 'cargo.numeric'  => 'El campo solo acepta valores numericos ! ',
            'password.required'  => ' Campo cargo con errores! ',
            'password.confirmed'  => ' La contraseña de confirmacion no coincide ! ',
        ]

    );

    // $validar_email  = Auth::where('email', trim($data['email']))->count();
    // if ($validar_email > 0) {
    //     return response([
    //         'error' => 'Este correo ya esta registrado en el sistema !  '
    //     ], 422);
    // }

    try {

        $user = User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'celular'  => $data['celular'],
            'nombreempresa'  => $data['nombreempresa'],
            'email' => $data['email'],
            'codCargo' => 3,
            'password' => bcrypt($data['password'])
        ]);

        $token = $user->createToken('main')->plainTextToken;
        $id    = $user['id'];
        ProjectUser::where('desCorreo', $data['email'])->update(['idIntegrante' =>  $id]);


        return response([
            'user' => $user,
            'token' => $token
        ]);

    } catch (Throwable $e) {
        // $enviar["mensaje"]  = $e;
        return response([
            'error' => ' Tenemos errores  ::'.$e
        ], 422);

    }


    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => [
                'required',
            ],
            'remember' => 'boolean'
        ]);
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'error'   => 'Ingrese las credenciales correctas! ',
                'message' => 'Ingrese las credenciales correctas! ',

            ], 422);
        }
        $user = Auth::user();
        $userId = $user->id;
        $token = $user->createToken('main')->plainTextToken;


        $sql = "

                select
                ad.desNombreProyecto

                from (
                select  pp.desNombreProyecto
                from proy_proyecto pp
                inner join conf_maestro_empresas mp on pp.desEmpresa  = mp.cod_Empresa
                inner join conf_ubigeo cu on pp.codUbigeo  = cu.codUbigeo
                where pp.id  = ?

                union all

                select pp.desNombreProyecto
                from proy_proyecto pp
                inner join proy_integrantes pi2  on pp.codProyecto = pi2.codProyecto
                inner join conf_maestro_empresas mp on pp.desEmpresa  = mp.cod_Empresa
                inner join conf_ubigeo cu on pp.codUbigeo  = cu.codUbigeo
                where pi2.idIntegrante   = ? and pi2.codEstadoInvitacion  = ?
                ) ad
                group by
                ad.desNombreProyecto

        ";


        $valores = array($userId, $userId , 1);
        $project = DB::select($sql, $valores);


        return response([
            'user' => $user,
            'topics' => $project,
            'token' => $token,
            'success' => true,
            'expiresAt' => 86400000
        ]);
    }

    public function logout()
    {
        /** @var User $user */
        $user = Auth::user();
        // Revoke the token that was used to authenticate the current request...
        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }

    public function info_person (Request $request) {
        $project = User::where('id', $request['id'])->get();

        return $project;
    }

    public function upd_person (Request $request) {
          $res = User::where('id', $request['id'])->update([
            'celular' => $request['celular'],
            'email'   => $request['email'],
            'lastname' => $request['lastname'],
            'name'     => $request['name'],
            'nombreempresa' => $request['nombreempresa'],
            'codCargo' => $request['codcargo']
          ]);

        return $res;
    }


    public function get_search_person(Request $request){
        // $data = $request->validate([
        //     'buscar' => 'required|string'
        // ]);
        $buscar_mail  = trim($request['buscar']);
        return $buscar_mail == '' ? array() :  User::select("users.id as id", "users.email as email")->where('email',$buscar_mail)->orWhere('email','like',"%{$buscar_mail}%")->get();

    }

}
