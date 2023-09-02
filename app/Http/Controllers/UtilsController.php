<?php

namespace App\Http\Controllers;
use App\Models\Sec_Cargo;
use App\Models\Proy_TipoProyecto;
use App\Models\Conf_Ubigeo;
use App\Models\Conf_Moneda;
use App\Models\Conf_Empresa;
use App\Models\Proy_AreaIntegrante;
use App\Models\Proy_RolIntegrante;
use App\Models\Conf_Tipodiaprogramacion;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UtilsController extends Controller
{

    public function get_cargos () {
        $secCargo = Sec_Cargo::all();
        return $secCargo;
    }

    public function get_tipoproyectos () {
        $proyProyecto = Proy_TipoProyecto::all();
        return $proyProyecto;
    }

    public function get_ubigeo () {
        $confUbigeo = Conf_Ubigeo::all(['codUbigeo','desUbigeo']);
        return $confUbigeo;
    }

    public function get_moneda () {
        $confMoneda = Conf_Moneda::all();
        return $confMoneda;
    }

    public function get_areaintegrante () {
        $areaIntegrante = Proy_AreaIntegrante::all();
        return $areaIntegrante;
    }

    public function get_proyrolintegrante() {
        $proyRolIntegrante = Proy_RolIntegrante::all();
        return $proyRolIntegrante;
    }

    public function get_utilitarios_proyecto(){

        $confUbigeo        = Conf_Ubigeo::all(['codUbigeo','desUbigeo']);
        $confMoneda        = Conf_Moneda::all();
        $areaIntegrante    = Proy_AreaIntegrante::all();
        $proyRolIntegrante = Proy_RolIntegrante::all();
        $proyTipoProyecto  = Proy_TipoProyecto::all();
        $programming_day_types = array();

        $project_confs     = Conf_Tipodiaprogramacion::all();
        foreach($project_confs as $project_conf) {
            $programming_day_types[] = ['value'         => $project_conf['codTipoDiaProgramacion'],
                                        'name'          => $project_conf['desTipoDiaProgramacion'],
                                        'typeFrequency' => $project_conf['desNombreCorto']
            ];
        }

        $pri                      = array();
        $pri['confUbigeo']        = $confUbigeo;
        $pri['confMoneda']        = $confMoneda;
        $pri['areaIntegrante ']   = $areaIntegrante;
        $pri['proyRolIntegrante'] = $proyRolIntegrante;
        $pri['proyTipoProyecto']  = $proyTipoProyecto;
        $pri['proyTipoDiaProgramacion']  = $programming_day_types;

        // $principal = array();
        // array_push($principal,(object)$confUbigeo,(object)$confMoneda,(object)$areaIntegrante,(object)$proyRolIntegrante);
        return $pri; //$pri;
    }

    public function get_search_empresa(Request $request){
        // $data = $request->validate([
        //     'buscar' => 'required|string'
        // ]);
        $buscar_empresa  = $request['buscar'];
        return Conf_Empresa::where('des_Empresa',$buscar_empresa)
    ->orWhere('des_Empresa','like',"%{$buscar_empresa}%")->get();

    }

    public function set_new_empresa(Request $request){
        $enviar                    = array();
        $enviar["mensaje"]         = "";
        $enviar["flag"]            = 0;
        $enviar["registro"]        = array();

        $data = $request->validate([
            'company' => 'required|string',
            'ruc'     => 'required|numeric'
        ]);

        $id = Conf_Empresa::insertGetId([
            'des_Empresa' => $request['company'],
            'num_Ruc'     => intval($request['ruc'])
        ]);

        if($id > 0){

            $enviar["mensaje"]  = "Se inserto con exito la empresa.";
            $enviar["flag"]     = 1;
            $enviar["registro"]["des_Empresa"] = $request['company'];
            $enviar["registro"]["cod_Empresa"] = $id;

        }else{

            $enviar["mensaje"]  = "No se pudo Insertar los valores!";


        }

      return $enviar;
    }





}
