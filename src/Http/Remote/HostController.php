<?php

namespace ivampiresp\Cocoa\Http\Remote;

use Illuminate\Http\Request;
use ivampiresp\Cocoa\Http\Controller;
use ivampiresp\Cocoa\Models\Host;

class HostController extends Controller
{
    /**
     * 获取主机的数据
     * 这个方法非常重要！！！
     * 如果返回 404，莱云则判断主机不存在，会发起删除请求。
     * 一般情况下，只需要返回主机数据即可。
     */
    public function show(Host $host)
    {
        return $this->success($host);
    }

    public function update(Request $request)
    {
        //
        $host = Host::where('host_id', $request->route('host'))->firstOrFail();

        $host->update($request->all());

        return $this->updated($host);
    }

    public function destroy(Request $request)
    {
        // 如果你想要拥有自己的一套删除逻辑，可以不处理这个。返回 false 即可。
        // return false;

        $host = Host::where('host_id', $request->route('host'))->firstOrFail();


        // 或者执行 Functions/HostController.php 中的 destroy 方法。

        $HostController = new \ivampiresp\Cocoa\Http\Remote\Functions\HostController();

        return $HostController->destroy($host);
    }
}
