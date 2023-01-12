<?php

namespace ivampiresp\Cocoa\Http\Remote;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use ivampiresp\Cocoa\Http\Controller;
use ivampiresp\Cocoa\Models\Admin;
use ivampiresp\Cocoa\Models\Server;

// use Illuminate\Http\Request;

class RemoteController extends Controller
{
    public function index()
    {
        $data = [
            'remote' => [
                'name' => config('remote.module_id'),
            ],
            'servers' => Server::all()->toArray()
        ];

        return $this->success($data);
    }

    public function login()
    {
        $admin = Admin::first();

        if (!$admin) {
            return $this->error('管理员不存在');
        }

        $str = Str::random(60);
        Cache::put('fast_login_' . $str, $admin, 60);

        return $this->created([
            'token' => $str,
            'url' => route('login', ['fast_login_token' => $str])
        ]);
    }
}
