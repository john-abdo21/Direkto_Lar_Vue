<?php

namespace App\Http\Controllers;
use App\Models\RestrictionMember;
use App\Models\Restriction;
use App\Models\ProjectUser;
use App\Models\PhaseActividad;
use App\Models\RestrictionPerson;
use App\Models\RestrictionFront;
use App\Models\RestrictionPhase;
use App\Models\Conf_Estado;
use App\Models\Ana_TipoRestricciones;
use App\Models\Proy_AreaIntegrante;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
// use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Config;
use Helper; // Important
use Carbon\Carbon;

class RestrictionController extends Controller
{
    public function get_restriction(Request $request) {
        $data = array();
        $query_restriction = "

        select fin.*,
        (
            select count(1) from anares_actividad aa
            where
            (aa.codEstadoActividad  < 3 and aa.dayFechaConciliada < DATE_FORMAT(CONVERT_TZ(NOW(), '+00:00', '-05:00'), '%Y-%m-%d 00:00:00'))

            and aa.codProyecto  = fin.codProyecto
         ) as indRetrasados,
         (
            select count(1) from anares_actividad aa
            where
            (aa.codEstadoActividad  < 3 and aa.dayFechaConciliada > DATE_FORMAT(CONVERT_TZ(NOW(), '+00:00', '-05:00'), '%Y-%m-%d 00:00:00'))

            and aa.codProyecto  = fin.codProyecto
         ) as indNoRetrasados

        from (
        select
        ad.codProyecto , ad.codEstado , ad.dayFechaCreacion , ad.desUsuarioCreacion,
        ad.codAnaRes, ad.desColOcultas, ad.desnombreproyecto , min(ad.isInvitado) as isInvitado

        from (
        select
        ar.*,
        pp.desNombreProyecto as desnombreproyecto,
        0 as isInvitado
        from anares_analisisrestricciones  ar
        inner join proy_proyecto pp  on ar.codProyecto  = pp.codProyecto
        where
        pp.id  =  ?

        union all

        select
        ar.*,
        pp.desNombreProyecto as desnombreproyecto,
        1 as isInvitado
        from anares_analisisrestricciones  ar
        inner join proy_proyecto pp  on ar.codProyecto  = pp.codProyecto
        inner join proy_integrantes pi2 on ar.codProyecto  = pi2.codProyecto
        inner join ana_integrantes ai on pi2.codProyIntegrante  = ai.codProyIntegrante
        where
        pi2.codEstadoInvitacion  = 1 and
        pi2.idIntegrante  = ?
        ) ad
        group BY ad.codProyecto , ad.codEstado , ad.dayFechaCreacion , ad.desUsuarioCreacion ,ad.indNoRetrasados,ad.indRetrasados,
        ad.codAnaRes, ad.desColOcultas, ad.desnombreproyecto
        ) fin


        ";
        $valores      = array($request['id'],$request['id']);
        $restrictions = DB::select($query_restriction, $valores);
        $restrictions = array_map(function ($value) {
            return (array)$value;
        }, $restrictions);

        // $restrictions     = Restriction::select('anares_analisisrestricciones.*','proy_proyecto.desNombreProyecto as desnombreproyecto')
        // ->join('proy_proyecto', 'proy_proyecto.codProyecto', '=', 'anares_analisisrestricciones.codProyecto')
        // ->where('proy_proyecto.id', $request['id'])
        // ->get();

        foreach($restrictions as $eachdata) {

            $dataRestrictions = array();

            $dataRestrictions = [
                'desnombreproyecto' => $eachdata['desnombreproyecto'],
                'codProyecto'      => $eachdata['codProyecto'],
                'codEstado'        => $eachdata['codEstado'],
                'dayFechaCreacion' => $eachdata['dayFechaCreacion'],
                'indNoRetrasados'  => $eachdata['indNoRetrasados'],
                'indRetrasados'    => $eachdata['indRetrasados'],
                'codAnaRes'        => $eachdata['codAnaRes'],
                'isInvitado'        => $eachdata['isInvitado'],
                'integrantes'      => [],
                'integrantesProy'  => []
            ];

            // $members = RestrictionMember::where('codAnaRes', $eachdata['codAnaRes']);
            $integrantes = RestrictionMember::select("ana_integrantes.*", "proy_integrantes.desCorreo as desProyIntegrante")
            ->Join('proy_integrantes', function($join){
                $join->on('proy_integrantes.codProyIntegrante', '=', 'ana_integrantes.codProyIntegrante');
                $join->on('proy_integrantes.codProyecto', '=', 'ana_integrantes.codProyecto');

            })
            ->where('ana_integrantes.codAnaRes', $eachdata['codAnaRes'])->get();

            $integrantes_Proy = ProjectUser::where('codProyecto', $eachdata['codProyecto'])->get();

            $dataIntegrantes = array();
            foreach($integrantes as $data1) {
                // $f = [
                //     'codProyecto' => $data['codProyecto'],
                //     'codAnaRes'   => $data['codAnaRes'],
                //     'codEstado'   => $data['codEstado'],
                //     'codProyIntegrante' => $data['codProyIntegrante'],
                //     'desProyIntegrante' => $data['desProyIntegrante'],
                // ];
                $dataIntegrantes[] = $data1['codProyIntegrante'];
                //array_push($dataIntegrantes, $f);
            }
            $dataRestrictions['integrantes'] = $dataIntegrantes;

            $dataIntegrantesProy = array();
            foreach($integrantes_Proy as $data2) {
                $d = [
                    'codProyIntegrante'  => $data2['codProyIntegrante'],
                    'codProyecto'        => $data2['codProyecto'],
                    'id'                 => $data2['id'],
                    'desCorreo'          => $data2['desCorreo']
                ];

                array_push($dataIntegrantesProy, $d);
            }
            $dataRestrictions['integrantesProy'] = $dataIntegrantesProy;

            array_push($data, $dataRestrictions);

        }


     return $data;

    }

    public function upd_numOrden(Request $request) {
        //$restrictionid = Restriction::where('codProyecto', $request['codProyecto'])->get('codAnaRes');
        $enviar =  array();
        $enviar["mensaje"] = "";
        $enviar["estado"]  = false;

        try {

            foreach($request['data'] as $info) {

                $resultado1 = PhaseActividad::where('codProyecto',(int)$request['codProyecto'])
                ->where('codAnaResActividad', $info['codAnaResActividad'])
                ->update([
                    'numOrden' =>  $info['index']
                ]);

            }

            $enviar["estado"]  = true;

        } catch (\Throwable $th) {
            $enviar["mensaje"] = "Tenemos errores y no se puede actualizar";
        }

     return $enviar;

    }

    public function update_state(Request $request) {
        //$restrictionid = Restriction::where('codProyecto', $request['codProyecto'])->get('codAnaRes');
        $enviar =  array();
        $enviar["mensaje"] = "";
        $enviar["estado"]  = false;

        try {

            $resultado = Restriction::where('codProyecto',(int)$request['codProyecto'])->update([
                'codEstado' => $request['state']
            ]);

            $enviar["estado"]  = true;

        } catch (\Throwable $th) {
            $enviar["mensaje"] = "Tenemos errores y no se puede actualizar";
        }

     return $enviar;

    }
    public function update_hidden(Request $request) {
        //$restrictionid = Restriction::where('codProyecto', $request['codProyecto'])->get('codAnaRes');
        $enviar =  array();
        $enviar["mensaje"] = "";
        $enviar["estado"]  = false;

        try {

            $resultado = Restriction::where('codProyecto',(int)$request['codProyecto'])->update([
                'desColOcultas' => trim($request['hidecolumns']) == "" ? " " : $request['hidecolumns']
            ]);

            $enviar["estado"]  = true;

        } catch (\Throwable $th) {
            $enviar["mensaje"] = "Tenemos errores y no se puede actualizar";
        }

     return $enviar;

    }
    public function update_member(Request $request) {

        $restrictionid = Restriction::where('codProyecto', $request['codProyecto'])->get('codAnaRes');
        $enviar =  array();
        $enviar["mensaje"] = "";
        $enviar["estado"]  = false;

        try {

            $checkuser = RestrictionMember::where('codProyecto', $request['codProyecto'])->delete();
            // print_r($request['users']);
            foreach($request['users'] as $user) {
                // print_r($user);
                //$projectuserid = ProjectUser::where('codProyecto', $request['codProyecto'])->where('desCorreo', $user)->get('codProyIntegrante');
                $restrictionmember = RestrictionMember::create([
                    'codProyecto' => $request['codProyecto'],
                    'codAnaRes' => $restrictionid[0]['codAnaRes'],
                    'codEstado' => 1,
                    'dayFechaCreacion' => date('Y-m-d H:i:s'),
                    'desUsuarioCreacion' => '',
                    'codProyIntegrante' => (String)$user
                ]);
            }

            $enviar["estado"] = true;

        } catch (\Throwable $th) {
            $enviar["mensaje"] = "Tenemos errores y no se puede actualizar";
        }

        return $enviar;
    }
    public function add_front(Request $request) {
        $codAnaRes = Restriction::where('codProyecto', $request['id'])->get('codAnaRes');
        $resFrente = RestrictionFront::insertGetId([
            'desAnaResFrente' => $request['name'],
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => '',
            'codProyecto' => $request['id'],
            'codAnaRes' => $codAnaRes[0]['codAnaRes'],
        ]);
        $enviar = array();
        $enviar["codFrente"] = $resFrente;
        return $enviar;
    }

    public function add_phase(Request $request) {
        $codAnaRes = Restriction::where('codProyecto', $request['projectid'])->get('codAnaRes');
        $resFase  = RestrictionPhase::insertGetId([
            'desAnaResFase' => $request['name'],
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => '',
            'codAnaResFrente' => $request['frontid'],
            'codProyecto' => $request['projectid'],
            'codAnaRes' => $codAnaRes[0]['codAnaRes'],
        ]);
        $enviar = array();
        $enviar["codFase"] = $resFase;
        return $enviar;
    }

    public function add_Actividad(Request $request) {
        $codAnaRes = Restriction::where('codProyecto', $request['projectid'])->get('codAnaRes');

        $resFront = PhaseActividad::create([
            'desActividad' => $request['name'],
            'desRestriccion' => "",
            'codTipoRestriccion' => 0,
            'dayFechaRequerida' => $request['date'],
            // 'idUsuarioResponsable' => 'Lizeth Marzano',
            // 'desAreaResponsable' => 'Lizeth Marzano',
            // 'codEstadoActividad' => 'En proceso',
            // 'codUsuarioSolicitante' => 'Lizeth Marzano',
            'codAnaResFase' => $request['phaseid'],
            'codAnaResFrente' => $request['frontid'],
            'codProyecto' => $request['projectid'],
            'codAnaRes' => $codAnaRes[0]['codAnaRes'],
        ]);
        return $request;
    }



    public function cron_enviar_notificacionDiaria(){
        $query_proyectos_retrasados = "


            select pp.codProyecto
            from  proy_proyecto pp
            where
            pp.codEstado   = 0 and
            (
                        select count(1) from anares_actividad aa
                        where
                        (aa.codEstadoActividad  < 3 and aa.dayFechaConciliada < DATE_FORMAT(CONVERT_TZ(NOW(), '+00:00', '-05:00'), '%Y-%m-%d 00:00:00'))

                        and aa.codProyecto  = pp.codProyecto
            ) > 0

        ";

        $proyectos  = DB::select($query_proyectos_retrasados);
        $proyectos = array_map(function ($value) {
            return (array)$value;
        }, $proyectos);

        foreach($proyectos as $proy) {
            $codProyecto       = $proy['codProyecto'];
            $query_actividades = "

                select
                aa.codAnaResActividad,
                af.desAnaResFrente as Frente ,
                af2.desAnaResFase as Fase,
                aa.desActividad as Actividad ,
                aa.desRestriccion as Restriccion,
                IFNULL(at2.desTipoRestricciones ,'sin elegir') as Tipo_Restriccion,
                IFNULL(concat(u.name,' '+u.lastname) , pi2.desCorreo) as Usuario_Responsable,
                pa.desArea as Area_Responsable,
                ce.desEstado as Estado_Actividad,
                concat(u2.name,' ',u2.lastname) as Usuario_Solicitante,
                aa.dayFechaRequerida,
                aa.dayFechaConciliada,
                aa.numOrden
                from
                anares_actividad aa
                inner join anares_frente af on aa.codAnaResFrente  = af.codAnaResFrente
                inner join anares_fase af2 on aa.codAnaResFase  = af2.codAnaResFase
                left join anares_tiporestricciones at2 on aa.codTipoRestriccion  = at2.codTipoRestricciones
                left join proy_integrantes pi2 on aa.idUsuarioResponsable = pi2.codProyIntegrante
                left join users u on pi2.idIntegrante  = u.id
                left join proy_areaintegrante pa on pi2.codArea  = pa.codArea
                left join conf_estado ce on aa.codEstadoActividad  = ce.codEstado and ce.desModulo = 'ANARES'
                left join users u2 on aa.codUsuarioSolicitante  = u2.id
                where aa.codProyecto = ? and
                (aa.codEstadoActividad  < 3 and aa.dayFechaConciliada < DATE_FORMAT(CONVERT_TZ(NOW(), '+00:00', '-05:00'), '%Y-%m-%d 00:00:00'))
                order by aa.codAnaResFrente,aa.codAnaResFase, aa.numOrden

            ";

            $valores        = array($codProyecto);
            $actividades    = DB::select($query_actividades, $valores);


            $integrantes  = RestrictionMember::select("proy_integrantes.desCorreo as correo", "proy_proyecto.desNombreProyecto as proyecto", "proy_integrantes.idIntegrante as idIntegrante")
            ->Join('proy_proyecto', 'proy_proyecto.codProyecto','=','ana_integrantes.codProyecto')
            ->leftJoin('proy_integrantes', function($join){
                $join->on('proy_integrantes.codProyIntegrante', '=', 'ana_integrantes.codProyIntegrante');
                $join->on('proy_integrantes.codProyecto', '=', 'ana_integrantes.codProyecto');

            })
            ->where('ana_integrantes.codProyecto', $codProyecto)
            ->where('proy_integrantes.codEstadoInvitacion', 1)
            ->get();

            foreach ($integrantes as $key => $value) {

                $datos_enviar = array();
                $datos_enviar['actividades']       = $actividades;
                $datos_enviar['des_correo']        = $value['correo'];
                $datos_enviar['des_proyecto']      = $value['proyecto'];
                $datos_enviar['des_link']          = Config::get('global.URL');
                $datos_enviar['des_direktor_icon'] = Config::get('global.ICON_DIREKTOR');

                Helper::enviarEmail($datos_enviar, 'retrasos', "Actividades con Retrasos en el proyecto ".$value['proyecto'], $value['idIntegrante'] ,$value['correo']);
            }

        }


    }
    public function push_enviar_notificaciones (Request $request){

        $enviar             = array();
        $enviar["flag"]     = 0;
        $enviar["mensaje"]  = "";
        $codProyecto        = $request['id'];
        $query_actividades  = "


        select
        pr.desNombreProyecto as proyecto,
        aa.codAnaResActividad,
        af.desAnaResFrente as Frente ,
        af2.desAnaResFase as Fase,
        aa.desActividad as Actividad ,
        aa.desRestriccion as Restriccion,
        IFNULL(at2.desTipoRestricciones ,'sin elegir') as Tipo_Restriccion,
        IFNULL(concat(u.name,' '+u.lastname) , pi2.desCorreo) as Usuario_Responsable,
        pa.desArea as Area_Responsable,
        ce.desEstado as Estado_Actividad,
        concat(u2.name,' ',u2.lastname) as Usuario_Solicitante,
        aa.dayFechaRequerida,
        aa.dayFechaConciliada,
        aa.numOrden
        from
        anares_actividad aa
        inner join proy_proyecto pr on aa.codProyecto = pr.codProyecto
        inner join anares_frente af on aa.codAnaResFrente  = af.codAnaResFrente
        inner join anares_fase af2 on aa.codAnaResFase  = af2.codAnaResFase
        left join anares_tiporestricciones at2 on aa.codTipoRestriccion  = at2.codTipoRestricciones
        left join proy_integrantes pi2 on aa.idUsuarioResponsable = pi2.codProyIntegrante
        left join users u on pi2.idIntegrante  = u.id
        left join proy_areaintegrante pa on pi2.codArea  = pa.codArea
        left join conf_estado ce on aa.codEstadoActividad  = ce.codEstado and ce.desModulo = 'ANARES'
        left join users u2 on aa.codUsuarioSolicitante  = u2.id
        where aa.flgNoti = 0 and aa.codProyecto = ?
        order by aa.codAnaResFrente,aa.codAnaResFase, aa.numOrden


        ";

        // try {


            $valores      = array($codProyecto);
            $actividades  = DB::select($query_actividades, $valores);
            $integrantes  = RestrictionMember::select("proy_integrantes.desCorreo as correo", "proy_proyecto.desNombreProyecto as proyecto", "proy_integrantes.idIntegrante as idIntegrante")
            ->Join('proy_proyecto', 'proy_proyecto.codProyecto','=','ana_integrantes.codProyecto')
            ->leftJoin('proy_integrantes', function($join){
                $join->on('proy_integrantes.codProyIntegrante', '=', 'ana_integrantes.codProyIntegrante');
                $join->on('proy_integrantes.codProyecto', '=', 'ana_integrantes.codProyecto');

            })
            ->where('ana_integrantes.codProyecto', $codProyecto)
            ->where('proy_integrantes.codEstadoInvitacion', 1)
            ->get();

            /* Enviamos los correos de notificación */

            foreach ($integrantes as $key => $value) {

                $datos_enviar = array();
                $datos_enviar['actividades']       = $actividades;
                $datos_enviar['des_correo']        = $value['correo'];
                $datos_enviar['des_proyecto']      = $value['proyecto'];
                $datos_enviar['des_link']          = Config::get('global.URL');
                $datos_enviar['des_direktor_icon'] = Config::get('global.ICON_DIREKTOR');

                Helper::enviarEmail($datos_enviar, 'alerta', "Correo de Seguimiento de Analisis de Restricciones - Proyecto ".$value['proyecto'], $value['idIntegrante'] ,$value['correo']);
            }

            foreach ($actividades as $key => $value) {


                $proyecto  = str_replace(' ', '', $value->proyecto);
                $mensaje   =  "La restriccion ".$value->codAnaResActividad." con nombre :".$value->Actividad." y de estado actual :  ".$value->Estado_Actividad.", fue actualizada, si requiere mas detalle del cambio consultar la web.";

                Helper::callNotification("ACTUALIZACIONES : Proyecto ".$value->proyecto, $mensaje, $proyecto);


            }

            /* Actualizamos el estado de la notificacion */
            $arr_ids = array();
            foreach ($actividades as $key0 => $value0) {
                $arr_ids[] = $value0->codAnaResActividad;
                $resultado = PhaseActividad::where('codAnaResActividad',(int)$value0->codAnaResActividad)->update([

                    'flgNoti' => 1,

                ]);


            }

            $enviar["flag"]                   =  1;
            $enviar["mensaje"]                = "Registros Actualizados !";
            $enviar["idsupd"]                 = $arr_ids;





        // } catch (\Throwable $e) {
        //     $enviar["mensaje"]                = $e;
        // }


        return $enviar;


    }

    public function upd_restricciones (Request $request){

        // $data = json_decode($request);
        $enviar = array();
        $enviar["flag"]     = 0;
        $enviar["inserciones"]     = array();
        $enviar["actualizaciones"]     = array();
        $enviar["mensaje"]  = "";
        // print_r($request);

        try {
            // print_r($request);
            foreach ($request['data'] as $value) {


                    $fecha     = str_replace("T", " ", $value['dayFechaRequerida']);
                    $fecha     = str_replace("Z", "", $fecha);

                    $fechac    = str_replace("T", " ", $value['dayFechaConciliada']);
                    $fechac    = str_replace("Z", "", $fechac);
                    $resultado = "";
                    $tiporesultado = "";
                    $fechaLeva     = "";



                    if($value['codAnaResActividad'] > 0){

                        $resultado = PhaseActividad::where('codAnaResActividad',(int)$value['codAnaResActividad'])->update([
                            'codTipoRestriccion' => $value['codTipoRestriccion'],
                            'desActividad'       => (string)$value['desActividad'],
                            'desRestriccion'     => (string)$value['desRestriccion'],
                            'idUsuarioResponsable'   => $value['idUsuarioResponsable'],
                            'codEstadoActividad'     => $value['codEstadoActividad'],
                            'dayFechaRequerida'      => ($fecha == 'null' || $fecha == '') ? null : $fecha,
                            'dayFechaConciliada'     => ($fechac == 'null' || $fechac == '') ? null : $fechac,
                            'flgNoti'                => 0,
                            'dayFechaLevantamiento'  => $value['codEstadoActividad'] == 3 ? Carbon::now() : null
                            // 'numOrden'               => $value['numOrden']
                        ]);

                        if($value['codEstadoActividad'] == 3){
                            $datos                        = array();

                            //$fechaLevantamiento = PhaseActividad::where('codAnaResActividad', (int)$value['codAnaResActividad'])->value('dayFechaCreacion');
                            $datos["idReal"]              = (int)$value['codAnaResActividad'];
                            $datos["fechaLevantamiento"]  = date("Y-m-d", strtotime(Carbon::now()));
                            $enviar["actualizaciones"][]  = $datos;

                        }



                        $tiporesultado = "upd";

                    }else{

                        $codAnaRes = Restriction::where('codProyecto', $request['projectId'])->get('codAnaRes');
                        $idactividad = PhaseActividad::insertGetId([
                            'codTipoRestriccion'     => $value['codTipoRestriccion'],
                            'desActividad'           => (string)$value['desActividad'],
                            'desRestriccion'         => (string)$value['desRestriccion'],
                            'idUsuarioResponsable'   => $value['idUsuarioResponsable'],
                            'codEstadoActividad'     => $value['codEstadoActividad'],
                            'dayFechaRequerida'      => ($fecha == 'null' || $fecha == '') ? null : $fecha,
                            'dayFechaConciliada'     => ($fechac == 'null' || $fechac == '') ? null : $fechac,
                            'codProyecto'            => $request['projectId'],
                            'codAnaRes'              => $codAnaRes[0]['codAnaRes'],
                            'codAnaResFase'          => $value['codAnaResFase'],
                            'codAnaResFrente'        => $value['codAnaResFrente'],
                            'codUsuarioSolicitante'  => $request['userId'],
                            'numOrden'               => $value['idrestriccion'] + 0.01,
                            'flgNoti'                => 0,
                            'dayFechaCreacion'       => Carbon::now()
                        ]);

                        $tiporesultado = "ins";

                        // $resultado = PhaseActividad::find($idactividad);
                        $fechaCreacion = PhaseActividad::where('codAnaResActividad', $idactividad)->value('dayFechaCreacion');

                        $datos                    = array();
                        $datos['idPivote']        = $value['codAnaResActividad'];
                        $datos["idReal"]          = $idactividad;
                        $datos["fechaIdentificacion"] = date("Y-m-d", strtotime($fechaCreacion));
                        $enviar["inserciones"][]  = $datos;

                    }


            }

            $enviar["flag"]                   =  1;
            $enviar["mensaje"]                = "Registros Actualizados !";
            $enviar["datos"]                  = $resultado;


        } catch (Throwable $e) {

            $enviar["mensaje"]  = $e;

        }

         return $enviar;

    }

    public function get_restrictionsMember(Request $request){
        $devolvemos_data = RestrictionMember::select("ana_integrantes.*", "proy_integrantes.desCorreo as desProyIntegrante", "proy_integrantes.codArea")
        ->leftJoin('proy_integrantes', function($join){
            $join->on('proy_integrantes.codProyIntegrante', '=', 'ana_integrantes.codProyIntegrante');
            $join->on('proy_integrantes.codProyecto', '=', 'ana_integrantes.codProyecto');

         })
         ->where('ana_integrantes.codProyecto', $request['id'])->get();
        // ->join('anares_analisisrestricciones', 'ana_integrantes.codAnaRes', '=', 'anares_analisisrestricciones.codAnaRes')
        // ->join('proy_integrantes', 'proy_integrantes.codProyIntegrante', '=', 'ana_integrantes.codProyIntegrante', ' and ','ana_integrantes.codProyecto','=','ana_integrantes.codProyecto')
        // ->where('anares_analisisrestricciones.codProyecto', $request['id'])->get();
        return $devolvemos_data;
    }

    public function get_estado(Request $request) {

        $datos_estado = Conf_Estado::where('desModulo', 'ANARES')->get();

        return $datos_estado;
    }

    public function get_data_restricciones(Request $request) {

        $query_integrantes = "

        select
        ai.*,
        case
        when isnull(u.name)
        then pi2.desCorreo
        else concat(u.name,' ',u.lastname)
        end as desProyIntegrante,
        pi2.codArea,
        pi2.idIntegrante,
        pi2.codRolIntegrante
        from ana_integrantes ai
        inner join proy_integrantes pi2
        left  join users u on pi2.desCorreo  = u.email
        on
        ai.codProyIntegrante  = pi2 .codProyIntegrante and
        ai.codProyecto        = pi2.codProyecto
        where ai.codProyecto = ?

        ";
        $rolUsuario   = 0; // en estos momentos el rolusuario cero es el creador del proyecto.
        $areaUsuario  = 0;
        $coduser      = $request['codsuser'];
        $valores      = array($request['id']);
        $integrantesAnaRes = DB::select($query_integrantes, $valores);
        $integrantesAnaRes = array_map(function ($value) {
            return (array)$value;
        }, $integrantesAnaRes);

        foreach ($integrantesAnaRes as $integrante) {
            if ($integrante['idIntegrante'] == $coduser){
                $rolUsuario  = $integrante['codRolIntegrante'];
                $areaUsuario = $integrante['codArea'];
                break;
            }
        }

        $frontdata   = RestrictionFront::where('codProyecto', $request['id'])->get();
        $restriction = Restriction::where('codProyecto', $request['id'])->get();
        $usuario     = $project = User::select('users.name','users.lastname')->where('id', $coduser)->get();

        //Auth::select('users.name','users.lastname')->where('id',$coduser)->get();

        $enviar      = array();
        $anaresdata  = [];
        $conteo      = 0;

        foreach($frontdata as $eachdata) {
            $dataFrente = [
                'codFrente'   => $eachdata['codAnaResFrente'],
                'desFrente'   => $eachdata['desAnaResFrente'],
                'isOpen'      => $conteo == 0 ? true : false,
                'listaFase'   => [],
            ];

            $phasedata   = RestrictionPhase::where('codAnaResFrente', $eachdata['codAnaResFrente'])->get();
            $conteo_fase = 0;
            foreach($phasedata as $sevdata) {
                $dataFase = [
                    'codFase' => $sevdata['codAnaResFase'],
                    'desFase' => $sevdata['desAnaResFase'],
                    'isOpen'  => true, //$conteo_fase == 0 ? true : false,
                    // 'notDelayed' => $restriction[0]['indNoRetrasados'],
                    // 'delayed' => $restriction[0]['indRetrasados'],
                    'listaRestricciones' => [],
                    'hideCols' => [],
                ];

                $Activedata = PhaseActividad::select("anares_actividad.*" , "anares_tiporestricciones.desTipoRestricciones as desTipoRestriccion" , "proy_integrantes.desCorreo as desUsuarioResponsable","proy_integrantes.idIntegrante", "proy_integrantes.codRolIntegrante",  "proy_integrantes.codArea"  ,"proy_areaintegrante.desArea", "conf_estado.desEstado as desEstadoActividad", "users.name as name", "users.lastname as lastname", "proy_proyecto.id as codCreador")
                ->leftjoin('anares_tiporestricciones', 'anares_actividad.codTipoRestriccion', '=', 'anares_tiporestricciones.codTipoRestricciones')
                ->leftJoin('proy_integrantes', function($join){
                    $join->on('proy_integrantes.codProyIntegrante', '=', 'anares_actividad.idUsuarioResponsable');
                    $join->on('proy_integrantes.codProyecto', '=', 'anares_actividad.codProyecto');
                 })
                 ->leftJoin('proy_proyecto', function($join){
                    $join->on('anares_actividad.codProyecto', '=', 'proy_proyecto.codProyecto');
                 })
                 ->leftJoin('proy_areaintegrante', function($join){
                    $join->on('proy_integrantes.codArea', '=', 'proy_areaintegrante.codArea');
                 })
                 ->leftJoin('conf_estado', function($join){
                    $join->on('anares_actividad.codEstadoActividad', '=', 'conf_estado.codEstado');
                 })
                 ->leftJoin('users', function($join){
                    $join->on('anares_actividad.codUsuarioSolicitante', '=', 'users.id');
                 })
                ->where('conf_estado.desModulo','=',  'ANARES')
                ->where('anares_actividad.codAnaResFase','=',  $sevdata['codAnaResFase'])
                ->where('anares_actividad.codAnaResFrente','=', $eachdata['codAnaResFrente'])
                ->orderBy('anares_actividad.numOrden', 'ASC')
                ->get();

                    foreach($Activedata as $data) {
                        $des_usuarioResponsable = $data['desUsuarioResponsable'];
                        foreach ($integrantesAnaRes as $integrante) {
                            if ($integrante['codProyIntegrante'] == $data['idUsuarioResponsable']){
                                $des_usuarioResponsable  = $integrante['desProyIntegrante'];
                                break;
                            }
                        }

                        $habilitado = false;
                        $frequerida_enabled  = false;
                        $fconciliada_enabled = false;
                        // Verificamos si esta habilitado el acceso a la modificacion.

                        if ( $data['codCreador'] == $coduser  || $rolUsuario == 3){

                            $habilitado = true;
                            $frequerida_enabled  = true;
                            $fconciliada_enabled = true;

                        }elseif ($data['codRolIntegrante'] == 2  && $data['idIntegrante']  == $coduser ) {

                            $habilitado = true;


                        }else{

                            $habilitado = false;
                        }

                        // $data['idIntegrante']

                        $restricciones = [
                            'codAnaResActividad' => $data['codAnaResActividad'],
                            'desActividad'       => $data['desActividad'],
                            'desRestriccion'     => $data['desRestriccion'],
                            'codTipoRestriccion' => $data['codTipoRestriccion'],
                            'desTipoRestriccion' => $data['desTipoRestriccion'],
                            'dayFechaRequerida'     => $data['dayFechaRequerida'] == null ? '' : date("Y-m-d", strtotime($data['dayFechaRequerida'])),  //$data['dayFechaRequerida'] == null ? '' : $data['dayFechaRequerida'],
                            'dayFechaConciliada'    => $data['dayFechaConciliada'] == null ? '' : date("Y-m-d", strtotime($data['dayFechaConciliada'])),  //$data['dayFechaConciliada'] == null ? '' : $data['dayFechaConciliada'],
                            'dayFechaIdentificacion'    => $data['dayFechaCreacion'] == null ? '' : date("Y-m-d", strtotime($data['dayFechaCreacion'])),
                            'dayFechaLevantamiento'    => $data['dayFechaLevantamiento'] == null ? '' : date("Y-m-d", strtotime($data['dayFechaLevantamiento'])),
                            'idUsuarioResponsable'  => $data['idUsuarioResponsable'],
                            'desUsuarioResponsable' => $des_usuarioResponsable ,
                            'desUsuarioSolicitante' => $data['name']." ".$data["lastname"],
                            'idUsuarioSolicitante' => (int)$data['codUsuarioSolicitante'],
                            'codEstadoActividad' => $data['codEstadoActividad'],
                            'desEstadoActividad' => $data['desEstadoActividad'],
                            'desAreaResponsable' => $data['desArea'],
                            'codAreaRestriccion' => $data['codArea'],
                            'numOrden'           => $data['numOrden'],
                            'flgNoti'            => $data['flgNoti'],
                            'isEnabled'          =>  $habilitado,
                            'isEnabledFRequerida' => $frequerida_enabled,
                            'isEnabledFConciliada'=> $fconciliada_enabled,
                            'isupdate'           => false
                            // 'applicant' => "Lizeth Marzano",
                        ];
                        array_push($dataFase['listaRestricciones'], $restricciones);
                    }
                array_push($dataFrente['listaFase'], $dataFase);
                $conteo_fase++;
            }
            array_push($anaresdata, $dataFrente);
            $conteo++;
        }

        $tipoRestricciones = Ana_TipoRestricciones::All();
        $areaIntegrante    = Proy_AreaIntegrante::all();
        $datos_estado = Conf_Estado::where('desModulo', 'ANARES')->get();

        $enviar['estadoRestriccion'] = $restriction[0]['codEstado'] == 0 ? true : false;
        $enviar['estados']           = $datos_estado;
        $enviar['integrantesAnaReS'] = $integrantesAnaRes;
        $enviar['areaIntegrante']    = $areaIntegrante;
        $enviar['tipoRestricciones'] = $tipoRestricciones;
        $enviar['restricciones']     = $anaresdata;
        $enviar['columnasOcultas']   = $restriction[0]['desColOcultas'];
        $enviar['solicitanteActual'] = $usuario[0]['name']." ".$usuario[0]['lastname'];
        $enviar['rolUsuario']        = $rolUsuario;
        $enviar['areaUsuario']       = $areaUsuario;

        return $enviar;
    }
    public function get_front(Request $request) {
        $frontdata = RestrictionFront::where('codProyecto', $request['id'])->get();
        $restriction = Restriction::where('codProyecto', $request['id'])->get();
        // $frontdata = RestrictionFront::where('codProyecto', '107')->get();
        // $restriction = Restriction::where('codProyecto', '107')->get();
        $anaresdata = [];

        foreach($frontdata as $eachdata) {
            $tempdata = [
                'id' => $eachdata['codAnaResFrente'],
                'name' => $eachdata['desAnaResFrente'],
                'isOpen' => false,
                'info' => [],
            ];

            $phasedata = RestrictionPhase::where('codAnaResFrente', $eachdata['codAnaResFrente'])->get();
            foreach($phasedata as $sevdata) {
                $temp = [
                    'id' => $sevdata['codAnaResFase'],
                    'name' => $sevdata['desAnaResFase'],
                    'notDelayed' => $restriction[0]['indNoRetrasados'],
                    'delayed' => $restriction[0]['indRetrasados'],
                    'tableData' => [],
                    'hideCols' => [],
                ];
                $Activedata = PhaseActividad::where('codAnaResFase','=',  $sevdata['codAnaResFase'])
                                    ->where('codAnaResFrente','=', $eachdata['codAnaResFrente'])
                                    ->get();
                    foreach($Activedata as $data) {
                        $temptable = [
                            'exercise' => $data['desActividad'],
                            // 'restriction' => $data['desRestriccion'],
                            'restriction' => "restriction",
                            'date_required' =>$data['dayFechaRequerida'],
                            'responsible' => $data['desActividad'],
                            'responsible_area' => "Arquitectura",
                            'applicant' => "Lizeth Marzano",
                        ];
                        array_push($temp['tableData'], $temptable);
                    }
                array_push($tempdata['info'], $temp);
            }
            array_push($anaresdata, $tempdata);
        }
        return $anaresdata;
    }


    public function delete_restriction(Request $request){
        $resultado = array();
        $resultado['flag']          = 0;
        $resultado['resultado']     = "";

        try {

            $resultado['resultado'] =  PhaseActividad::where('codAnaResActividad', $request['codAnaResActividad'])->delete();
            $resultado['flag']      =  1;

        } catch (\Throwable $th) {

            $resultado['resultado'] =  $th;


        }

      return $resultado;
    }

    public function duplicate_restriction(Request $request){
        $resultado = array();
        $resultado['flag']          = 0;
        $resultado['resultado']     = "";

        try {

            // $consulta  =  PhaseActividad::where('codAnaResActividad', 19)->get();
            // $consulta2 = $consulta->replicate()->save();
            // $consulta2 = $consulta2->codAnaResActividad;
            $consulta        = PhaseActividad::find($request['codAnaResActividad'])->replicate();
            $arrayconsulta   = $consulta->toArray();
            $arrayconsulta['numOrden']         =  $arrayconsulta['numOrden'] + 0.001;
            $arrayconsulta['dayFechaCreacion'] =  Carbon::now();
            $arrayconsulta['dayFechaLevantamiento'] =  Carbon::now();
            $newCreatedModel = PhaseActividad::create($arrayconsulta);
            // $newID      = $consulta->id;

            $resultado['resultado']              =  $newCreatedModel['codAnaResActividad'];
            $resultado['dayFechaIdentificacion'] =  date("Y-m-d", strtotime($newCreatedModel['dayFechaCreacion']));
            $resultado['flag']      =  1;

        } catch (\Throwable $th) {

            $resultado['resultado'] =  $th;


        }

      return $resultado;
    }

    public function delete_front(Request $request) {

        // if ($request['phaseLen'] == 0 || $request['phaseId'] == '-999') {

        if ($request['phaseId'] == '-999' || $request['phaseId'] == 0 || $request['phaseId'] == null) {

            try {

            PhaseActividad::where('codAnaResFrente', $request['frontId'])->delete();
            RestrictionPhase::where('codAnaResFrente', $request['frontId'])->delete();
            RestrictionFront::where('codAnaResFrente', $request['frontId'])->delete();

                 }
            catch (\Throwable $th) {}
            // RestrictionFront::where('codAnaResFrente', $request['frontId'])->delete();
            // try {
            //     RestrictionPhase::where('codAnaResFrente', $eachdata['frontId'])->delete();
            // } catch (\Throwable $th) {}

        } else {

            try {
            PhaseActividad::where('codAnaResFase', $request['phaseId'])->delete();
            } catch (\Throwable $th) {}
            RestrictionPhase::where('codAnaResFase', $request['phaseId'])->delete();
            // $frontdata = RestrictionFront::where('codAnaResFrente', $request['frontId'])->get();
            // foreach($frontdata as $eachdata) {
            //     RestrictionPhase::where('codAnaResFase', $request['phaseId'])->delete();
            // }
        }
        return $request;
    }

    public function get_restriction_p(Request $request) {
        $TipoRestricciones = RestrictionPerson::where('codTipoRestricciones', '>=', 0)->get();
        return $TipoRestricciones;
    }

    public function add_restriction(Request $request) {
        $TipoRestricciones = RestrictionPerson::where('codTipoRestricciones', '>=', 0)->delete();
        $index = $request['index'];
        for ($i=0; $i < $index; $i++) {
            if($request['data'][$i]['value'] == '') {
                $i -= 1;
                $index -= 1;
            }
            else {
                $resFront = RestrictionPerson::create([
                    'codTipoRestricciones' => $i,
                    'desTipoRestricciones' => $request['data'][$i]['value'],
                ]);
            }
        }
        $TipoRestricciones = RestrictionPerson::where('codTipoRestricciones', '>=', 0)->get();
        return $TipoRestricciones;
    }

    /* Upload Excel */
    public function uploadExcel(Request $request,$id,$projectId){
        try{
            // Get the uploaded file
            $file = $request->excelFile;
            // Load the spreadsheet
            $spreadsheet = IOFactory::load($file);
            // Get the active sheet
            $worksheet = $spreadsheet->getActiveSheet();
            // Get the highest row number and column letter
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            // Loop through each row of the sheet
            $error = false;
            $success = false;
            $errors = [];
            for ($row = 2; $row <= $highestRow; $row++) {
                $error_ar = NULL;
                // Get the row data as an array
                $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false)[0];
                $Frente = $rowData[0];
                $Fase = $rowData[1];
                $Actividad = $rowData[2];
                $Restriccion = $rowData[3];
                $TipoRestriccion = $rowData[4];
                $FechaRequerida = $rowData[5];
                $FechaConciliada = $rowData[6];
                $Responsable = $rowData[7];
                $Estado = $rowData[8];
                $Solicitante = $rowData[9];
                if(gettype($FechaConciliada)=="integer"){
                    if(gettype($FechaRequerida)=="integer"){
                        /* check in anares_tiporestricciones */
                        $check_anares_tiporestricciones = Ana_TipoRestricciones::where('desTipoRestricciones',$TipoRestriccion)->first();
                        if($check_anares_tiporestricciones){
                            /* check in proy_integrantes*/
                            $sql_proy_integrantes = "

                            select * from (
                                select
                                a.codProyIntegrante,
                                case when u.id is not null then
                                concat(u.name,' ', u.lastname)
                                else
                                a.desCorreo
                                end as nombre
                                from proy_integrantes a
                                left join users u on a.idIntegrante  = u.id
                                where codProyecto = ".$projectId."
                            ) c where c.nombre  = '".$Responsable."'

                            ";

                            // $valores      = array($projectId, $Responsable);
                            $proy_integrantes = DB::select($sql_proy_integrantes);
                            $proy_integrantes = collect($proy_integrantes)->first();

                            // $proy_integrantes = array_map(function ($value) {
                            //     return (array)$value;
                            // }, $proy_integrantes);

                            // $proy_integrantes = ProjectUser::where(['codProyecto'=>$projectId,'desCorreo'=>$Responsable])->first();
                            if(!empty($proy_integrantes)){
                                /* check in ana_integrantes */
                                $check_ana_integrantes = RestrictionMember::where('codProyIntegrante',$proy_integrantes->codProyIntegrante);
                                if($check_ana_integrantes){
                                    /* check in conf_estado */
                                    $conf_estado = Conf_Estado::where(['desEstado'=>$Estado,'desModulo'=>'ANARES'])->first();
                                    if($conf_estado){
                                        $anaRes = Restriction::where('codProyecto', $projectId)->get('codAnaRes');
                                        $codAnaRes = $anaRes[0]['codAnaRes'];

                                        $anares_actividad = new PhaseActividad;

                                        /* Check already frente exists */
                                        $frente_already = false;
                                        $fase_already = false;
                                        $anares_frente = RestrictionFront::where(['desAnaResFrente'=>$Frente,'codProyecto'=>$projectId,'codAnaRes'=>$codAnaRes])->first();
                                        if($anares_frente){
                                            $frente_already = true;
                                            $anares_fase = RestrictionPhase::where(['codAnaRes'=>$codAnaRes,'desAnaResFase'=>$Fase,'codProyecto'=>$projectId,'codAnaResFrente'=>$anares_frente->codAnaResFrente])->first();
                                            if($anares_fase){
                                                $fase_already = true;
                                            }
                                            else{
                                                $anares_fase = new RestrictionPhase;
                                                $anares_fase->desAnaResFase = $Fase;
                                                $anares_fase->codProyecto = $projectId;
                                                $anares_fase->codAnaRes = $codAnaRes;
                                            }
                                        }
                                        else{
                                            $anares_frente = new RestrictionFront;
                                            $anares_fase = new RestrictionPhase;

                                            /* Add Values */
                                            $anares_frente->desAnaResFrente = $Frente;
                                            $anares_frente->codProyecto = $projectId;
                                            $anares_frente->codAnaRes = $codAnaRes;

                                            $anares_fase->desAnaResFase = $Fase;
                                            $anares_fase->codProyecto = $projectId;
                                            $anares_fase->codAnaRes = $codAnaRes;
                                        }
                                        $anares_actividad->desActividad = $Actividad;
                                        // $anares_actividad->codEstadoActividad = $conf_estado->codEstado;
                                        $anares_actividad->desRestriccion = $Restriccion;

                                        $anares_actividad->codProyecto = $projectId;
                                        $anares_actividad->codAnaRes = $codAnaRes;

                                        $anares_actividad->codTipoRestriccion    = $check_anares_tiporestricciones->codTipoRestricciones;
                                        $anares_actividad->dayFechaRequerida     = gettype($FechaRequerida)=="integer" ? (Date::excelToDateTimeObject($FechaRequerida))->format('Y-m-d') : date('Y-m-d H:i:s');
                                        $anares_actividad->dayFechaConciliada    = gettype($FechaConciliada)=="integer" ? (Date::excelToDateTimeObject($FechaConciliada))->format('Y-m-d') : date('Y-m-d H:i:s');
                                        $anares_actividad->dayFechaCreacion      = Carbon::now();
                                        $anares_actividad->idUsuarioResponsable  = $proy_integrantes->codProyIntegrante;
                                        $anares_actividad->codUsuarioSolicitante = $id;

                                        $anares_actividad->codEstadoActividad = $conf_estado->codEstado;
                                        if($Solicitante){
                                            // $anares_actividad->codEstadoActividad = $id;
                                        }
                                        if(!$frente_already) $anares_frente->save();
                                        if($anares_frente){
                                            if(!$fase_already){
                                                $anares_fase->codAnaResFrente = $anares_frente->codAnaResFrente;
                                                $anares_fase->save();
                                            }
                                            if($anares_fase){
                                                $anares_actividad->codAnaResFrente = $anares_frente->codAnaResFrente;
                                                $anares_actividad->codAnaResFase = $anares_fase->codAnaResFase;
                                                if($anares_actividad->save()){
                                                    $success = true;
                                                }
                                                else $error = true;
                                            }
                                            else $error = true;
                                        }
                                        else $error = true;
                                    }
                                    else $error_ar = ["row" => "> Error en la fila: ".$row,'value' => "'desEstado' and 'desModulo'=>'ANARES' no coinciden en esta fila."];
                                }
                                else $error_ar = ["row" => "> Error en la fila: ".$row,'value' => "'codProyIntegrante' el valor no se encuentra en la fila"];
                            }
                            else $error_ar = ["row" => "> Error en la fila: ".$row,'value' => "'codProyecto'=>$projectId and 'desCorreo'=>$Responsable no se encuentran"];
                        }
                        else $error_ar = ["row" => "> Error en la fila: ".$row,'value' => "'desTipoRestricciones'=>$TipoRestriccion no se encontro el valor en el maestro de tipo de restricciones"];
                    }
                    else $error_ar = ["row" => "> Error en la fila: ".$row,'value' => "'dayFechaRequerida'=>$FechaRequerida la fecha no se puede ingresar, el formato es incorrecto."];
                }
                else $error_ar = ["row" => "> Error en la fila: ".$row,'value' => "'dayFechaConciliada'=>$FechaConciliada la fecha no se puede ingresar, el formato es incorrecto."];
                if($error_ar){
                    $error = true;
                    array_push($errors,$error_ar);
                }
            }
            return ["success"=>$success,"error" => $error,"successMessage"=> "Los registros fuerón importados con exito ! ", "errorMessage"=>"Algunos registros no pueden ser importados !","errors" => $errors];
        }
        catch (\Exception $e) {
            return ["error"=>true,"message"=>$e->getMessage()];
        }
    }
}
