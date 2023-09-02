<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;

class Conf_Tipodiaprogramacion extends Model
{
    use HasFactory;
    protected $fillable = [
        'codTipoDiaProgramacion',
        'desTipoDiaProgramacion',
        'desNombreCorto'
    ];
    protected $table = "conf_tipodiaprogramacion";

    public $timestamps = false;

}
