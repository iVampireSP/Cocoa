<?php

namespace ivampiresp\Cocoa\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceAllow extends Model
{
    public $fillable = [
        'device_id',
        'type',
        'topic',
        'action',
    ];
}
