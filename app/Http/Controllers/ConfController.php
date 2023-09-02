<?php

namespace App\Http\Controllers;
use App\Models\Conf_Tipodiaprogramacion;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ConfController extends Controller
{

    public function get_programmingdaytypes () {
        $programming_day_types = array();

        $project_confs = Conf_Tipodiaprogramacion::all();
        foreach($project_confs as $project_conf) {
            $programming_day_types[] = ['value'         => $project_conf['codTipoDiaProgramacion'],
                                        'name'          => $project_conf['desTipoDiaProgramacion'],
                                        'typeFrequency' => $project_conf['desNombreCorto']
            ];
        }
        return $programming_day_types;
    }


}
