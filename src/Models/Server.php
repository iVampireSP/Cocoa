<?php

namespace ivampiresp\Cocoa\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'name',
        'fqdn',
        'port',
        'username',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'username',
    ];
}
