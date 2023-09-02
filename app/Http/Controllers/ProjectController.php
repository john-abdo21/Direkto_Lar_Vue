<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\ProjectReport;
use App\Models\ProjectUtilReport;
use App\Models\Restriction;
use App\Models\RestrictionMember;
use App\Models\User;
use App\Models\Notification;
use App\Models\Notification_User;
use App\Models\Notification_User4;
use App\Models\ProjectConf;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Models\conf_colacorreos;
use App\Jobs\SendEmails;
use Config;
use Helper; // Important
use DB;

class ProjectController extends Controller
{
    //foreach($request['reports'] as $key=>$report) {
    public function update_projects_without_approve(Request $request){
        // $estado = '';
        $enviar =  array();
        $enviar["mensaje"] = "";
        $enviar["estado"]  = false;

        try {

            foreach($request['data'] as $key => $info) {

                $estado = $info['confirmar'] == 'true' ? 1 : ($info['confirmar'] == 'false' ? -1 : '');
                ProjectUser::where('codProyIntegrante', $info['codProyIntegrante'])->update([
                    'codEstadoInvitacion'            => $estado,
                    'dayFechaInvitacionConfirmacion' => date('Y-m-d H:i:s')
                ]);


            }

            $enviar["estado"]  = true;
        }catch (\Throwable $th) {
            $enviar["mensaje"] = "Tenemos errores y no se puede actualizar";
        }

        return $enviar;

    }
    public function projects_without_approve(Request $request){

        $enviar =  array();
        $enviar["mensaje"] = "";
        $enviar["estado"]  = false;

        try {

           $cod_usuario = (string)$request['id'];

            $usuario            = User::where('id', $cod_usuario)->get('email');
            // print($usuario);
            $enviar["datos"]    = ProjectUser::select('proy_integrantes.codEstadoInvitacion as confirmar', 'users.email as mail', 'proy_proyecto.desNombreProyecto as proyecto' , 'proy_integrantes.codProyIntegrante as codProyIntegrante')
            ->join('proy_proyecto', 'proy_proyecto.codProyecto', '=', 'proy_integrantes.codProyecto')
            ->join('users', 'users.id', '=', 'proy_integrantes.id')
            ->where('proy_integrantes.desCorreo', $usuario[0]->email)
            ->where('proy_integrantes.codEstadoInvitacion',0)
            ->get();

            $enviar["estado"]  = true;

        } catch (\Throwable $th) {
            $enviar["mensaje"] = "Tenemos errores y no se puede actualizar";
        }

        return $enviar;

    }


    public function create_project (Request $request) {
        $mail = false;
        $data = $request->validate([
            'projectName' => 'required|string',
            'business' => 'required|numeric',
            'projectType' => 'required|numeric',
            'district' => 'required|numeric',
            'country' => 'required|string',
        ]);

        $codPro = Project::insertGetId([
            'desNombreProyecto' => $request['projectName'],
            'codEstado' => 0,
            'desEmpresa' => $request['business'],
            'numPlazo' => intval($request['term']),
            'id' => $request['id'] | 0,
            'numAreaTechado' => intval($request['coveredArea']),
            'codTipoProyecto' => intval($request['projectType']),
            'codUbigeo' => intval($request['district']),
            'dayFechaInicio' => $request['startDate'],
            'numMontoReferencial' => $request['referenceAmount'],
            'numAreaTechada' => intval($request['area']),
            'numAreaConstruida' => intval($request['builtArea']),
            'desPais' => $request['country'],
            'desDireccion' => $request['address'],
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => $request['usersum'],
            'codMoneda' => intval($request['codMoneda'])
        ]);

        foreach($request['userInvData'] as $user) {

            $userEmail = $user['userEmail'];
            $userRole  = $user['userRole'];
            $userArea  = $user['userArea'];
            $userId    = isset($user['id']) ? $user['id'] : -999;

            if(ltrim(rtrim($userEmail)) != '' &&  ltrim(rtrim($userRole)) != '' && ltrim(rtrim($userRole)) != '')
            {

                $usercreate = ProjectUser::create([
                    'codProyecto'         => $codPro,
                    'id'                  => $request['id'],
                    'codEstadoInvitacion' => $request['id'] == $user['id'] ? 1 : 0,
                    'codRolIntegrante'    => intval($userRole),
                    'dayFechaInvitacion'  => $request['date'],
                    'codArea'             => intval($userArea),
                    'desCorreo'           => strval($userEmail),
                    'idIntegrante'        => $userId
                ]);

            }

            if(!isset($user['id']) ){

                $datos_enviar = array();
                $datos_enviar['des_correo']        = $userEmail;
                $datos_enviar['des_proyecto']      = $request['projectName'];
                $datos_enviar['des_link_register'] = Config::get('global.URL_REGISTRO');
                $datos_enviar['des_direktor_icon'] = Config::get('global.ICON_DIREKTOR');


                Helper::enviarEmail($datos_enviar, 'invitacion', "Correo de Invitación", $request['id'] ,$userEmail);

                // $conf_colacorreos                    = new conf_colacorreos;
                // $conf_colacorreos->desMensaje        = view('emails.invitation',$datos_enviar)->render();
                // $conf_colacorreos->dayFechaRegistro  = date('Y-m-d H:i:s');
                // $conf_colacorreos->desMotivo         = "Correo de Invitación";
                // // "dayFechaEnvio" => date('Y-m-d H:i:s'),
                // $conf_colacorreos->codUsuarioRegistro = $request['id'];
                // $conf_colacorreos->desCorreoEnvio     = $userEmail;
                // if($conf_colacorreos->save()) $mail   = true;
            }


        }


        $useremail = User::where('id', $request['id'])->get('email');
        $restrictioncreate = Restriction::create([
            'codProyecto' => $codPro,
            'codEstado' => 0,
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => $useremail[0]['email'],
            'indNoRetrasados' => 0,
            'indRetrasados' => 0,
        ]);
        /* $restrictionid = Restriction::where('codProyecto', $codPro[0]['codProyecto'])->get('codAnaRes');
        $restrictionmember = RestrictionMember::create([
            'codProyecto' => $codPro[0]['codProyecto'],
            'codAnaRes' => $restrictionid[0]['codAnaRes'],
            'codEstado' => 1,
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => '',
        ]); */
        foreach($request['reports'] as $report) {
            /* create util_reportes table record */
            $utilreportcreate = ProjectUtilReport::create([
                'desUtilReportes' => $report['reportSchedule']
            ]);
            $codUtilPro = ProjectUtilReport::get('codUtilReportes');
            $cod = $codUtilPro[sizeof($codUtilPro)-1]['codUtilReportes'];

            /* create proy_proyectoreportes table records */
            if($report['massiveStatus'] === true || $report['applyAllStatus'] === true) {
                $reportcreate = ProjectReport::create([
                    'codUtilReportes' => $cod,
                    'codProyecto' => $codPro,
                    'flagReporteMasivo' => $report['massiveStatus'],
                    'flagApplyAllStatus' => $report['applyAllStatus'],
                    'codTipoFrecuencia' => $request['typeFrequency'],
                    'dayFechaCreacion' => $request['date'],
                    'desUsuarioCreacion' => '',
                    'desCorreoEnvios' => '',
                    'codfrecuenciaenvioreporte' => $report['frequency']
                ]);
            }
            else {
                foreach($report['frequencies'] as $frequency) {
                    $reportcreate = ProjectReport::create([
                        'codUtilReportes' => $cod,
                        'codProyecto' => $codPro,
                        'flagReporteMasivo' => $report['massiveStatus'],
                        'flagApplyAllStatus' => $report['applyAllStatus'],
                        'codTipoFrecuencia' => $request['typeFrequency'],
                        'dayFechaCreacion' => $request['date'],
                        'desUsuarioCreacion' => '',
                        'desCorreoEnvios' => $frequency['user'],
                        'codfrecuenciaenvioreporte' => $frequency['freq']
                    ]);
                }
            }
        }

        /* crate proy_proyectoconf table record */
        $project_conf_create = ProjectConf::create([
            'codProyecto'               => $codPro,
            'codTipoDiaProgramacion'    => $request['programmingDayTypeCode']
        ]);

        // return ["codPro" => $codPro,"mail" => $mail,"message" => $mail ? "Emails were sent successfully" : ''];
        return $this->get_project($request);
    }

    public function sendMails($id){
        // $success = false;
        // $results = \DB::table('conf_colacorreos')->where('codUsuarioRegistro',$id)->whereNull('dayFechaEnvio')->get();
        // // dd($results);
        // if($results){
        //     foreach ($results as $key => $mail) {
        //         $res = Mail::to($mail->desCorreoEnvio)->send(new InvitationEmail('token'));
        //         if($res){
        //             $insertMail = \DB::table('conf_colacorreos')->where('codColaCorreo',$mail->codColaCorreo)->update([
        //                 "dayFechaEnvio" => date('Y-m-d H:i:s')
        //             ]);
        //             if($insertMail) $success = true;
        //             else $success = false;
        //         }
        //     }
        //     return ["success"=>$success,"message"=> $success ? "Emails were sent successfully." : "Something went wrong!"];
        // }
    }

    public function get_project (Request $request) {
        // $project = Project::where('id', $request['id'])->get();

        // $project = Project::select('proy_proyecto.*', 'conf_maestro_empresas.des_Empresa as nombreEmpresa', 'conf_ubigeo.desUbigeo as desUbigeo')
        // ->join('conf_maestro_empresas', 'proy_proyecto.desEmpresa', '=', 'conf_maestro_empresas.cod_Empresa')
        // ->join('conf_ubigeo', 'proy_proyecto.codUbigeo', '=', 'conf_ubigeo.codUbigeo')
        // ->where('proy_proyecto.id', $request['id'])
        // ->get();
        $query = "

            select
            ad.codProyecto ,ad.desNombreProyecto,ad.codEstado, ad.id, ad.desEmpresa ,ad.numPlazo,
            ad.numAreaTechada,ad.codTipoProyecto, ad.codUbigeo,ad.dayFechaInicio, ad.numMontoReferencial,
            ad.numAreaTechada , ad.numAreaConstruida, ad.desPais, ad.desDireccion, ad.dayFechaCreacion,
            ad.desUsuarioCreacion, ad.codMoneda, ad.nombreEmpresa, ad.desUbigeo , min(ad.isInvitado) as isInvitado

            from (
            select  pp.* , mp.des_Empresa as nombreEmpresa, cu.desUbigeo  as desUbigeo, 0 as isInvitado
            from proy_proyecto pp
            inner join conf_maestro_empresas mp on pp.desEmpresa  = mp.cod_Empresa
            inner join conf_ubigeo cu on pp.codUbigeo  = cu.codUbigeo
            where pp.id  = ?

            union all

            select pp.* , mp.des_Empresa as nombreEmpresa, cu.desUbigeo  as desUbigeo , 1 as isInvitado
            from proy_proyecto pp
            inner join proy_integrantes pi2  on pp.codProyecto = pi2.codProyecto
            inner join conf_maestro_empresas mp on pp.desEmpresa  = mp.cod_Empresa
            inner join conf_ubigeo cu on pp.codUbigeo  = cu.codUbigeo
            where pi2.idIntegrante   = ? and pi2.codEstadoInvitacion  = ?
            ) ad
            group by
            ad.codProyecto ,ad.desNombreProyecto,ad.codEstado, ad.id, ad.desEmpresa ,ad.numPlazo,
            ad.numAreaTechada,ad.codTipoProyecto, ad.codUbigeo,ad.dayFechaInicio, ad.numMontoReferencial,
            ad.numAreaTechada , ad.numAreaConstruida, ad.desPais, ad.desDireccion, ad.dayFechaCreacion,
            ad.desUsuarioCreacion, ad.codMoneda, ad.nombreEmpresa, ad.desUbigeo


        ";

        $valores = array($request['id'], $request['id'] , 1);
        $project = DB::select($query, $valores);

        return $project;
    }
    public function edit_project (Request $request) {
        // $timestamp = strtotime($request['startDate']);
        // $fechaInicio  = date("d-m-Y", $timestamp);

        $update = Project::where('codProyecto', $request['projectId'])->update([
            'desNombreProyecto' => $request['projectName'],
            'codEstado' => 0,
            'desEmpresa' => $request['business'],
            'numPlazo' => intval($request['term']),
            'numAreaTechado' => intval($request['coveredArea']),
            'codTipoProyecto' => intval($request['projectType']),
            'codUbigeo' => intval($request['district']),
            'dayFechaInicio' => $request['startDate'],
            'numMontoReferencial' => $request['referenceAmount'],
            'numAreaTechada' => intval($request['area']),
            'numAreaConstruida' => intval($request['builtArea']),
            'desPais' => $request['country'],
            'desDireccion' => $request['address'],
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => $request['usersum'],
            'codMoneda' => $request['codMoneda']
        ]);

        // ProjectUser::where('codProyecto', $request['projectId'])->delete();
        // print_r($request['userInvData']);
        // return ;
        $codProyIntegrantesExist = array();
        foreach($request['userInvData'] as $user) {
            $codProyIntegrantesExist[] = $user['codProyIntegrante'];

        }

        ProjectUser::where('codProyecto', $request['projectId'])
                     ->whereNotIn('codProyIntegrante', $codProyIntegrantesExist)
                     ->delete();

        foreach($request['userInvData'] as $user) {

            $userEmail = $user['userEmail'];
            $userRole  = $user['userRole'];
            $userArea  = $user['userArea'];
            $userId    = isset($user['id']) ? $user['id'] : -999;

            /* Este es un usuario que ya fue agregado y tiene un ID , aqui no se actualiza el estado de la invitacion */
            if ($user['codProyIntegrante'] > 0){

                ProjectUser::where('codProyecto', $request['projectId'])
                ->where('codProyIntegrante', $user['codProyIntegrante'])
                ->update([
                    'codArea'          => $user['userArea'],
                    'codRolIntegrante' => $user['userRole']
                    // 'desCorreo'        => $user['userEmail'],
                ]);

            }else{

                /* Significa que es un usuario que tiene un ID osea esta en el sistema  , se le va a invitar*/
                if ($userId  > 0){

                    $usercreate = ProjectUser::create([
                        'codProyecto'         => $request['projectId'],
                        'id'                  => $request['id'],
                        'codEstadoInvitacion' => 0,
                        'codRolIntegrante'    => intval($userRole),
                        'dayFechaInvitacion'  => $request['date'],
                        'codArea'             => intval($userArea),
                        'desCorreo'           => strval($userEmail),
                        'idIntegrante'        => $userId
                    ]);

                }else{
                /* Esto hace referencia que el correo no tiene usuario en el sistema*/

                    $usercreate = ProjectUser::create([
                        'codProyecto'         => $request['projectId'],
                        'id'                  => $request['id'],
                        'codEstadoInvitacion' => 0,
                        'codRolIntegrante'    => intval($userRole),
                        'dayFechaInvitacion'  => $request['date'],
                        'codArea'             => intval($userArea),
                        'desCorreo'           => strval($userEmail),
                        'idIntegrante'        => $userId
                    ]);

                    /* Se le envia un correo de invitacion */

                    $datos_enviar = array();
                    $datos_enviar['des_correo']        = $userEmail;
                    $datos_enviar['des_proyecto']      = $request['projectName'];
                    $datos_enviar['des_link_register'] = Config::get('global.URL_REGISTRO');
                    $datos_enviar['des_direktor_icon'] = Config::get('global.ICON_DIREKTOR');

                    Helper::enviarEmail($datos_enviar, 'invitacion', "Correo de Invitación", $request['id'] ,$userEmail);



                }
            }

            // if(ltrim(rtrim($userEmail)) != '' &&  ltrim(rtrim($userRole)) != '' && ltrim(rtrim($userRole)) != '')
            // {






            // }


        }

        $codUtilReportes = ProjectReport::select('codUtilReportes')->groupBy('codUtilReportes')->where('codProyecto', $request['projectId'])->get();
        /* update util_reports table */
        foreach($request['reports'] as $key=>$report) {
            if ($key < sizeof($codUtilReportes)) {
                ProjectUtilReport::where('codUtilReportes', $codUtilReportes[$key]['codUtilReportes'])->update([
                    'desUtilReportes' => $report['reportSchedule']
                ]);
            } else {
                ProjectUtilReport::create([
                    'desUtilReportes' => $report['reportSchedule']
                ]);
            }
        }

        /* update proy_proyectoconf table */
        ProjectConf::where('codProyecto', $request['projectId'])->update([
            'codTipoDiaProgramacion' => $request['programmingDayTypeCode']
        ]);

        $codUtilPro = ProjectUtilReport::get('codUtilReportes');
        $db_reports = ProjectReport::select('codUtilReportes')->groupBy('codUtilReportes')->where('codProyecto', $request['projectId'])->get();
        $day_fecha_creacions = array();
        foreach($db_reports as $db_report) {
            $db_cod_util = $db_report['codUtilReportes'];
            $day_fecha_creacion = ProjectReport::select('dayFechaCreacion')->where('codUtilReportes', $db_cod_util)->first();
            $day_fecha_creacions[] = $day_fecha_creacion;
        }
        ProjectReport::where('codProyecto', $request['projectId'])->delete();
        foreach($request['reports'] as $key=>$report) {
            //$new_day_fecha_creation = $key < $db_reports->count() ? $old_fecha_creacion['dayFechaCreacion'] : $request['date'];
            $new_cod_util_report = $key < $db_reports->count() ? $db_reports[$key]['codUtilReportes'] : $codUtilPro[sizeof($codUtilPro)-1]['codUtilReportes'];
            $new_day_fecha_creation = $key < $db_reports->count() ? $day_fecha_creacions[$key]['dayFechaCreacion'] : $request['date'];
            if($report['massiveStatus'] === true || $report['applyAllStatus'] === true) {
                $reportcreate = ProjectReport::create([
                    'codUtilReportes' => $new_cod_util_report,
                    'codProyecto' => $request['projectId'],
                    'flagReporteMasivo' => $report['massiveStatus'],
                    'flagApplyAllStatus' => $report['applyAllStatus'],
                    'codTipoFrecuencia' => $request['typeFrequency'],
                    'dayFechaCreacion' => $new_day_fecha_creation,
                    'desUsuarioCreacion' => '',
                    'desCorreoEnvios' => '',
                    'codfrecuenciaenvioreporte' => $report['frequency']
                ]);
            }
            else {
                foreach($report['frequencies'] as $frequency) {
                    $reportcreate = ProjectReport::create([
                        'codUtilReportes' => $new_cod_util_report,
                        'codProyecto' => $request['projectId'],
                        'flagReporteMasivo' => $report['massiveStatus'],
                        'flagApplyAllStatus' => $report['applyAllStatus'],
                        'codTipoFrecuencia' => $request['typeFrequency'],
                        'dayFechaCreacion' => $new_day_fecha_creation,
                        'desUsuarioCreacion' => '',
                        'desCorreoEnvios' => $frequency['user'],
                        'codfrecuenciaenvioreporte' => $frequency['freq']
                    ]);
                }
            }
        }

        // $enviar        = array();
        // $enviar['id']  = $request['id'];
        // $get_projects  = $this->get_project($request);

        return $this->get_project($request);
    }

    public function get_projectreport (Request $request) {
        /* $project = ProjectReport::where('codProyecto', $request['projectId'])->get();
        return $project; */
        $reports = array();

        $cod_util_reportes = ProjectReport::select('codUtilReportes')->groupBy('codUtilReportes')->where('codProyecto', $request['projectId'])->get();
        $project_users = ProjectUser::select('desCorreo')->where('codProyecto', $request['projectId'])->get();
        $usercreation = '';
        foreach ($project_users as $project_user) {
            $usercreation .= $project_user['desCorreo'] . ", ";
        }
        $pro_day = ProjectConf::select('codTipoDiaProgramacion')->where('codProyecto', $request['projectId'])->first();
        $pro_day_val = $pro_day['codTipoDiaProgramacion'];

        foreach ($cod_util_reportes as $cod_util_reporte) {
            /* get massive flag & apply all status flag */
            $flags = ProjectReport::select('flagReporteMasivo', 'flagApplyAllStatus')->where('codUtilReportes', $cod_util_reporte['codUtilReportes'])->first();
            $flag_massive = $flags['flagReporteMasivo'] === 1 ? true : false;
            $flag_apply_all = $flags['flagApplyAllStatus'] === 1 ? true : false;
            $report_text = ProjectUtilReport::select('desUtilReportes')->where('codUtilReportes', $cod_util_reporte['codUtilReportes'])->first();
            $report_text_val = $report_text['desUtilReportes'];

            $frequencies = array();
            /* when massive or apply all status flag true */
            if ($flag_massive == 1 || $flag_apply_all == 1) {
                $frequency = ProjectReport::select('codfrecuenciaenvioreporte')->where('codUtilReportes', $cod_util_reporte['codUtilReportes'])->first();
                $frequency_val = $frequency['codfrecuenciaenvioreporte'];
                $users = ProjectUser::select('desCorreo')->where('codProyecto', $request['projectId'])->get();
                foreach($users as $user) {
                    $frequencies[] = ['user' => $user['desCorreo'], 'freq' => $frequency_val];
                }

                $record =  ['applyAllStatus' => $flag_apply_all,
                            'frequencies' => $frequencies,
                            'frequency' => $frequency_val,
                            'massiveStatus' => $flag_massive,
                            'reportSchedule' => $report_text_val,
                            'proDayCode' => $pro_day_val,
                            'usercreation' => $usercreation,
                ];

            } else { /* neither massive nor apply all status */
                /* users & frequencies from proy_proyectoreportes table */
                $userFs = ProjectReport::select('desCorreoEnvios', 'codfrecuenciaenvioreporte')->where('codUtilReportes', $cod_util_reporte['codUtilReportes'])->get();
                foreach($userFs as $userF) {
                    $frequencies[] = ['user' => $userF['desCorreoEnvios'], 'freq' => $userF['codfrecuenciaenvioreporte']];
                }

                $record =  ['applyAllStatus' => $flag_apply_all,
                            'frequencies' => $frequencies,
                            'frequency' => null,
                            'massiveStatus' => $flag_massive,
                            'reportSchedule' => $report_text_val,
                            'proDayCode' => $pro_day_val,
                            'usercreation' => $usercreation,
                ];
            }
            $reports[] = $record;
        }
        return $reports;
    }
    public function get_projectuser (Request $request) {
        $projectuser = ProjectUser::where('codProyecto', $request['projectId'])->get();
        return $projectuser;
    }

    /* ************************** DESARROLLADOR  POR EL PROGRAMADOR  ******************************* */

    public function get_proy_applicant(Request $request)
    {
        $project_applicants = PhaseActividad::select('codUsuarioSolicitante')->groupBy('codUsuarioSolicitante')->where('codProyecto', $request['projectId'])->get();
        $users = User::whereIn('id', $project_applicants)->get();
        return $users;
    }

    public function get_notification(Request $request)
    {
        $cod_notifications = Notification_User4::where('id', $request['id'])->where('codEstado', 0)->get();
        $messages = array();
        foreach($cod_notifications as $cod_notification) {
            $message = Notification::where('codNotificacion', $cod_notification['codNotificacion'])->first();
            $messages[] = [
                'codNotificacionUsuario' => $cod_notification['codNotificacionUsuario'],
                'codNotificacion' => $message['codNotificacion'],
                'desNombre' => $message['desNombre'],
                'desDescripción' => $message['desDescripción'],
                'desPersonalizar' => $message['desPersonalizar']
            ];
        }

        return $messages;

    }

    public function update_cod_notification(Request $request)
    {
        $updated = Notification_User4::where('codNotificacionUsuario', $request['cod_notification_usuario'])->update([
           'codEstado' => 1
        ]);
        return $request['cod_notification_usuario'];
    }

    public function register_notification(Request $request)
    {
        $id = $request['id'];
        $date = $request['date'];
        $email = $request['email'];

        $created = Notification_User4::insertGetId([
            'id' => $id,
            'codNotificacion' => 1,
            'codEstado' => 0,
            'dayFechaCreacion' => $date,
            'desUsuarioCreación' => $email
        ]);
        return Notification_User4::where('codNotificacionUsuario', $created)->first();
    }


    /* ************************** DESARROLLADOR  POR EL PROGRAMADOR  ******************************* */

    public function update_state(Request $request) {
        //$restrictionid = Restriction::where('codProyecto', $request['codProyecto'])->get('codAnaRes');
        $enviar =  array();
        $enviar["mensaje"] = "";
        $enviar["estado"]  = false;

        try {

            $resultado1 = Project::where('codProyecto',(int)$request['codProyecto'])->update([
                'codEstado' => $request['state']
            ]);

            $resultado2 = Restriction::where('codProyecto',(int)$request['codProyecto'])->update([
                'codEstado' => $request['state']
            ]);

            $enviar["estado"]  = true;

        } catch (\Throwable $th) {
            $enviar["mensaje"] = "Tenemos errores y no se puede actualizar";
        }

     return $enviar;

    }
}
