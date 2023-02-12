<?php

namespace ivampiresp\Cocoa\Http\Remote;

use App\Actions\HostAction;
use App\Models\Host;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ivampiresp\Cocoa\Http\Controller;

class HostController extends Controller
{
    /**
     * 获取主机的数据
     * 这个方法非常重要！！！
     * 如果返回 404，莱云则判断主机不存在，会发起删除请求。
     * 一般情况下，只需要返回主机数据即可。
     */
    public function show(Host $host): JsonResponse
    {
        return $this->success($host);
    }

    public function update(Request $request): JsonResponse
    {
        $host = Host::where('host_id', $request->route('host'))->firstOrFail();

        $host->update($request->all());

        return $this->updated($host);
    }

    public function destroy(Request $request): JsonResponse
    {
        $host = Host::where('host_id', $request->route('host'))->firstOrFail();

        $HostController = new \App\Http\Controllers\Api\HostController();
        $HostController->destroy($host);

        return $HostController->destroy($host);
    }

    public function calculate(Request $request): JsonResponse
    {
        $hostAction = new HostAction();

        $price = $hostAction->calculatePrice($request->all());

        return $this->success([
            'price' => $price,
        ]);
    }
}
