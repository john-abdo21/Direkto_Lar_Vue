<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'codProyecto',
        'desNombreProyecto',
        'codEstado',
        'id',
        'desEmpresa',
        'numPlazo',
        'numAreaTechado',
        'codTipoProyecto',
        'codUbigeo',
        'dayFechaInicio',
        'numMontoReferencial',
        'numAreaTechada',
        'numAreaConstruida',
        'desPais',
        'desDireccion',
        'dayFechaCreacion',
        'desUsuarioCreacion',
        'codMoneda'
    ];
    protected $table = "proy_proyecto";
    protected $primaryKey = 'codProyecto';

    public $timestamps = false;

}
