<?php

namespace ivampiresp\Cocoa\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    public $fillable = [
        'name',
        'password',
        'client_id',
    ];
}
