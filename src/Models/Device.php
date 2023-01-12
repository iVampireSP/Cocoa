<?php

namespace ivampiresp\Cocoa\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'password',
        'client_id',
    ];
    public mixed $id;
    public mixed $name;
}
