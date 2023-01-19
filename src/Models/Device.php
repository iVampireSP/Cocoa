<?php

namespace ivampiresp\Cocoa\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use Cachable;
    public $fillable = [
        'name',
        'password',
        'client_id',
    ];
}
