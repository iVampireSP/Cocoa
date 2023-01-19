<?php

namespace ivampiresp\Cocoa\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class DeviceAllow extends Model
{
    use Cachable;

    public $fillable = [
        'device_id',
        'type',
        'topic',
        'action',
    ];
}
