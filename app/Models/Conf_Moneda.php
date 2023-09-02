<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;

class Conf_Moneda extends Model
{
    use HasFactory;
    protected $fillable = [
        'codMoneda',
        'desMoneda',
        'desSimbolo'
    ];
    protected $table = "conf_moneda";

    public $timestamps = false;

}
