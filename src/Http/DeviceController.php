<?php

namespace ivampiresp\Cocoa\Http;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use ivampiresp\Cocoa\Models\Device;
use ivampiresp\Cocoa\Models\DeviceAllow;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $devices = Device::paginate(100);

        return view('Cocoa::devices.index', compact('devices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function store(Request $request): Response|RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:devices',
            'password' => 'required|min:8|max:32',
            'client_id' => 'nullable',
        ]);

        $device = Device::create($request->all());

        return redirect()->route('devices.edit', $device)->with('success', '设备创建成功');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('Cocoa::devices.create');
    }

    /**
     * Display the specified resource.
     *
     * @param Device $device
     *
     * @return RedirectResponse
     */
    public function show(Device $device): RedirectResponse
    {
        return redirect()->route('devices.edit', $device);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Device $device
     *
     * @return View
     */
    public function edit(Device $device): View
    {
        return view('Cocoa::devices.edit', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Device  $device
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Device $device): RedirectResponse
    {
        $request->validate([
            'password' => 'required|min:8|max:32',
            'client_id' => 'nullable',
        ]);


        // 检测 name 重复
        if ($request->input('name') != $device->name) {
            $request->validate([
                'name' => 'required|unique:devices',
            ]);
        }

        $device->update($request->all());

        // if password dirty
        $device->isDirty('password');

        return redirect()->route('devices.edit', $device)->with('success', '设备更新成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Device $device
     *
     * @return RedirectResponse
     */
    public function destroy(Device $device): RedirectResponse
    {
        $device->delete();

        return redirect()->route('devices.index')->with('success', '设备删除成功。');
    }


    public function allows(Device $device): View
    {
        $allows = DeviceAllow::where('device_id', $device->id)->get();

        return view('Cocoa::devices.allows.index', compact('device', 'allows'));
    }

    public function store_allow(Request $request, Device $device): RedirectResponse
    {
        $request->validate([
            'topic' => 'required',
            'action' => 'required|in:allow,deny',
            'type' => 'required|in:subscribe,publish'
        ]);


        // 检测冲突
        $conflict = DeviceAllow::where('device_id', $device->id)
            ->where('topic', $request->topic)
            ->where('type', $request->type)
            ->first();

        // 如果已经有同意的同 type 同 topic 的规则，就不再添加
        if ($conflict && $conflict->action == 'allow') {
            return back()->with('error', '已经有同意的同 type 同 topic 的规则，不再添加')->withInput();
        }

        // if ($conflict) {
        //     if ($conflict->action !== $request->action) {
        //         return back()->with('error', '已存在相同的规则。')->withInput();
        //     }

        //     if ($conflict->topic === $request->topic) {
        //         return back()->with('error', '已存在相同的规则。')->withInput();
        //     }
        // }

        DeviceAllow::create([
            'device_id' => $device->id,
            'topic' => $request->topic,
            'action' => $request->action,
            'type' => $request->type
        ]);

        return redirect()->route('devices.allows.index', $device)->with('success', '设备权限创建成功。');
    }

    public function allow_destroy($allow): RedirectResponse
    {
        //
        // $allow->delete();
        DeviceAllow::find($allow)->delete();

        return back()->with('success', '设备权限删除成功。');
    }

    public function online(Request $request): View
    {
        $page = $request->input('page', 1);
        $clients = $this->http->get('devices', [
            'page' => $page,
        ])->json();

        return view('Cocoa::devices.online', compact('clients'));
    }

    public function online_destroy(Request $request): RedirectResponse
    {
        $this->http->delete('devices', [
            'client_id' => $request->input('client_id'),
            'name' => $request->input('name'),
        ]);

        return back()->with('success', '设备下线成功。');
    }
}
