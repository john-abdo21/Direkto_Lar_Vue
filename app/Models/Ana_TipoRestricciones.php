<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;

class Ana_TipoRestricciones extends Model
{
    use HasFactory;
    protected $fillable = [
        'codTipoRestricciones',
        'desTipoRestricciones'
    ];
    protected $table      = "anares_tiporestricciones";
    protected $primaryKey = 'codTipoRestricciones';
    public $timestamps    = false;

}
