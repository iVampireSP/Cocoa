<?php

namespace ivampiresp\Cocoa\Http\Remote;

use App\Http\Controllers\Remote\ServerStatusController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use ivampiresp\Cocoa\Http\Controller;
use ivampiresp\Cocoa\Models\Admin;

// use Illuminate\Http\Request;

class RemoteController extends Controller
{
    public function index(): JsonResponse
    {
        $data = [
            'remote' => [
                'name' => config('remote.module_id'),
            ],
        ];

        $servers = (new ServerStatusController())->index();

        $data['servers'] = $servers;

        return $this->success($data);
    }

    public function login(): JsonResponse
    {
        $admin = Admin::first();

        if (! $admin) {
            return $this->error('管理员不存在');
        }

        $str = Str::random(60);
        Cache::put('fast_login_'.$str, $admin, 60);

        return $this->created([
            'token' => $str,
            'url' => route('login', ['fast_login_token' => $str]),
        ]);
    }
}
