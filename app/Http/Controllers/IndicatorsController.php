<?php

namespace App\Http\Controllers;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Models\Restriction;
use App\Models\ProjectUser;
use App\Models\PhaseActividad;
use App\Models\RestrictionPerson;
use App\Models\RestrictionFront;
use App\Models\RestrictionPhase;
use App\Models\Ana_TipoRestricciones;
use App\Models\Proy_AreaIntegrante;
use App\Models\Conf_Estado;


use DB;
use Config;
use Helper; // Important
use Carbon\Carbon;


class IndicatorsController extends Controller
{


    public function get_project_indicators (Request $request) {
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

    public function get_project_restricciones(Request $request) {

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
        // $rolUsuario   = 0; // en estos momentos el rolusuario cero es el creador del proyecto.
        // $areaUsuario  = 0;
        // $coduser      = $request['codsuser'];
        $valores      = array($request['codProyecto']);
        $integrantesAnaRes = DB::select($query_integrantes, $valores);
        $integrantesAnaRes = array_map(function ($value) {
            return (array)$value;
        }, $integrantesAnaRes);

        // foreach ($integrantesAnaRes as $integrante) {
        //     if ($integrante['idIntegrante'] == $coduser){
        //         $rolUsuario  = $integrante['codRolIntegrante'];
        //         $areaUsuario = $integrante['codArea'];
        //         break;
        //     }
        // }

        $frontdata   = RestrictionFront::where('codProyecto', $request['codProyecto'])->get();
        $restriction = Restriction::where('codProyecto', $request['codProyecto'])->get();
        // $usuario     = $project = User::select('users.name','users.lastname')->where('id', $coduser)->get();

        //Auth::select('users.name','users.lastname')->where('id',$coduser)->get();

        $enviar            = array();
        $anarestricciones  = [];
        $conteo            = 0;

        foreach($frontdata as $eachdata) {
            // $dataFrente = [
            //     'codFrente'   => $eachdata['codAnaResFrente'],
            //     'desFrente'   => $eachdata['desAnaResFrente'],
            //     'isOpen'      => $conteo == 0 ? true : false,
            //     'listaFase'   => [],
            // ];

            $phasedata   = RestrictionPhase::where('codAnaResFrente', $eachdata['codAnaResFrente'])->get();
            $conteo_fase = 0;
            foreach($phasedata as $sevdata) {
                // $dataFase = [
                //     'codFase' => $sevdata['codAnaResFase'],
                //     'desFase' => $sevdata['desAnaResFase'],
                //     'isOpen'  => true, //$conteo_fase == 0 ? true : false,
                //     // 'notDelayed' => $restriction[0]['indNoRetrasados'],
                //     // 'delayed' => $restriction[0]['indRetrasados'],
                //     'listaRestricciones' => [],
                //     'hideCols' => [],
                // ];

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

                        // $habilitado = false;
                        // $frequerida_enabled  = false;
                        // $fconciliada_enabled = false;
                        // // Verificamos si esta habilitado el acceso a la modificacion.

                        // if ( $data['codCreador'] == $coduser  || $rolUsuario == 3){

                        //     $habilitado = true;
                        //     $frequerida_enabled  = true;
                        //     $fconciliada_enabled = true;

                        // }elseif ($data['codRolIntegrante'] == 2  && $data['idIntegrante']  == $coduser ) {

                        //     $habilitado = true;


                        // }else{

                        //     $habilitado = false;
                        // }

                        // $data['idIntegrante']

                        $restricciones = [

                            'codAnaResFrente' => $eachdata['codAnaResFrente'],
                            'desAnaResFrente' => $eachdata['desAnaResFrente'],
                            'codAnaResFase'   => $sevdata['codAnaResFase'],
                            'desAnaResFase'   => $sevdata['desAnaResFase'],
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
                            // 'isEnabled'          =>  $habilitado,
                            // 'isEnabledFRequerida' => $frequerida_enabled,
                            // 'isEnabledFConciliada'=> $fconciliada_enabled,
                            'isupdate'           => false
                            // 'applicant' => "Lizeth Marzano",
                        ];
                         array_push($anarestricciones, $restricciones);
                    }
                // array_push($dataFrente['listaFase'], $dataFase);
                // $conteo_fase++;
            }
            // array_push($anaresdata, $dataFrente);
            // $conteo++;
        }

        $tipoRestricciones = Ana_TipoRestricciones::All();
        $areaIntegrante    = Proy_AreaIntegrante::all();
        $datos_estado = Conf_Estado::where('desModulo', 'ANARES')->get();

        $enviar['estadoRestriccion'] = $restriction[0]['codEstado'] == 0 ? true : false;
        $enviar['estados']           = $datos_estado;
        $enviar['integrantesAnaReS'] = $integrantesAnaRes;
        $enviar['areaIntegrante']    = $areaIntegrante;
        $enviar['tipoRestricciones'] = $tipoRestricciones;
        $enviar['restricciones']     = $anarestricciones;
        $enviar['columnasOcultas']   = $restriction[0]['desColOcultas'];
        // $enviar['solicitanteActual'] = $usuario[0]['name']." ".$usuario[0]['lastname'];
        // $enviar['rolUsuario']        = $rolUsuario;
        // $enviar['areaUsuario']       = $areaUsuario;

        return $enviar;
        }


}

