<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectConf extends Model
{
    use HasFactory;

    protected $fillable = [
        'codProyectoconf',
        'codProyecto',
        'codTipoDiaProgramacion'
    ];

    protected $table = 'proy_proyectoconf';
    protected $primaryKey = 'codProyectoconf';
    public $timestamps = false;
}

