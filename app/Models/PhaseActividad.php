<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhaseActividad extends Model
{
    use HasFactory;

    protected $fillable = [
        'codAnaResActividad',
        'desActividad',
        'desRestriccion',
        'codTipoRestriccion',
        'dayFechaRequerida',
        'dayFechaCreacion',
        'dayFechaConciliada',
        'dayFechaLevantamiento',
        'idUsuarioResponsable',
        'desAreaResponsable',
        'codEstadoActividad',
        'codUsuarioSolicitante',
        'codAnaResFase',
        'codAnaResFrente',
        'codProyecto',
        'codAnaRes',
        'numOrden'
    ];

    protected $table = 'anares_actividad';
    protected $primaryKey = 'codAnaResActividad';
    public $timestamps = false;
    //sadsdsd vermos que
}
