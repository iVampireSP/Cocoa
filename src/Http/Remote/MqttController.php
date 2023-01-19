<?php

namespace ivampiresp\Cocoa\Http\Remote;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ivampiresp\Cocoa\Http\Controller;
use ivampiresp\Cocoa\Models\Device;
use ivampiresp\Cocoa\Models\DeviceAllow;

class MqttController extends Controller
{
    public function authentication(Request $request): JsonResponse
    {
        $client_id = $request->input('client_id');
        $device_id = $request->input('device_id');
        $password = $request->input('password');

        $device = Device::where('name', $device_id)->first();

        if (!$device) {
            return $this->notFound('No device found');
        }

        if ($device->client_id) {
            if ($device->client_id != $client_id) {
                return $this->forbidden('客户端 ID 不匹配');
            }
        }

        if ($device->password == $password) {
            return $this->success([
                'result' => true,
            ]);
        }

        return $this->forbidden('用户名或密码错误');
    }

    public function authorization(Request $request): JsonResponse
    {
        $device_id = $request->input('device_id');

        $topic = $request->input('topic');

        $type = $request->input('type');

        $device = Device::where('name', $device_id)->first();

        if (!$device) {
            return $this->notFound('设备不存在');
        }

        $device_allows = DeviceAllow::where('device_id', $device->id)
            ->where('type', $type)
            ->get();

        foreach ($device_allows as $device_allow) {

            // 先精确匹配
            if ($device_allow->topic == $topic) {
                // Log::info('精确匹配', [
                //     'topic' => $topic,
                //     'device_allow' => $device_allow->toArray(),
                // ]);
                if ($device_allow->action == 'deny') {
                    return $this->forbidden('禁止订阅');
                }
            }

            // 将 topic 转换成适合模糊搜索的格式
            $topic = str_replace('#', '%', $topic);
            $topic = str_replace('+', '_', $topic);

            // 将 #,%,+ 转换成 *
            $allow_topic = str_replace('%', '*', $device_allow->topic);
            $allow_topic = str_replace('_', '*', $allow_topic);
            $allow_topic = str_replace('#', '*', $allow_topic);

            // Log::debug('$device_allow->topic', [$allow_topic]);

            if (fnmatch($allow_topic, $topic)) {
                return $this->success([
                    'result' => true,
                ]);
            }
        }


        return $this->forbidden('禁止访问');
    }
}
