<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'codNotificacion',
        'desNombre',
        'desDescripción',
        'desPersonalizar',
    ];
    protected $table = "other_notificaciones";

    public $timestamps = false;

}
