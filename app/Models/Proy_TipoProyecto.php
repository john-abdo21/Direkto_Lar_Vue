<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;

class Proy_TipoProyecto extends Model
{
    use HasFactory;
    protected $fillable = [
        'codTipoProyecto',
        'desTipoProyecto'
    ];
    protected $table = "proy_tipoproyecto";

    public $timestamps = false;

}
