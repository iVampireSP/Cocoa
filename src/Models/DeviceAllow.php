<?php

namespace ivampiresp\Cocoa\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceAllow extends Model
{
    use HasFactory;

    public $fillable = [
        'device_id',
        'type',
        'topic',
        'action',
    ];
}
