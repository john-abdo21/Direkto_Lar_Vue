<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;

class Conf_Estado extends Model
{
    use HasFactory;
    protected $fillable = [
        'codEstado',
        'desEstado',
        'desModulo'
    ];
    protected $table      = "conf_estado";
    // protected $primaryKey = 'codEstado';
    public $timestamps    = false;

}
