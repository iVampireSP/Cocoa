<?php

namespace ivampiresp\Cocoa\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'mac',
        'netmask',
        'gateway',
        'nameservers',
        'interface',
        'ip_host_id',
    ];

    protected $casts = [
        'nameservers' => 'array',
    ];

    // 路由模型绑定
    public function getRouteKeyName()
    {
        return 'ip_host_id';
    }
}
