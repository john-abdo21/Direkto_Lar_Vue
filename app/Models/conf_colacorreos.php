<?php

namespace App\Models;

use App\Jobs\SendEmails;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conf_colacorreos extends Model
{
    protected $guarded = [];
    use HasFactory;

    protected static function booted()
    {
        static::created(function () {
            SendEmails::dispatch();
        });
    }
}
