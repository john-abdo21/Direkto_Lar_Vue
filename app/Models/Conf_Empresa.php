<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;

class Conf_Empresa extends Model
{
    use HasFactory;
    protected $fillable = [
        'cod_Empresa',
        'des_Empresa',
        'num_Ruc'
    ];
    protected $table      = "conf_maestro_empresas";
    protected $primaryKey = 'cod_Empresa';
    public $timestamps    = false;

}
