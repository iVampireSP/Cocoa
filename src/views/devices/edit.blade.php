@section('title', '设备:' . $device->name)

<x-app-layout>
    <h3>{{ $device->name }}</h3>

    <a href="{{ route('devices.allows.index', $device) }}">编辑授权</a>


    <form method="POST" action="{{ route('devices.update', $device) }}" class="mt-3">
        @csrf
        @method('PATCH')

        <div class="input-group mt-1">
            <span class="input-group-text">{{ config('remote.module_id') }}.</span>
            <input type="text" class="form-control" id="name" name="name" placeholder="名称"
                   value="{{ $device->name }}" required/>
        </div>
        <span class="form-text text-muted">登录 MQTT 时的用户名，比如 {{ config('remote.module_id') }}.device_name。其中
            {{ config('remote.module_id') }} 是模块 ID, device_name 是上面输入的名字。</span>

        <div class="form-group mt-1">
            <label for="password">密码</label>
            <input type="text" class="form-control" id="password" name="password" value="{{ $device->password }}"
                   placeholder="密码" required autocomplete="off" autocorrect="off">
            <span
                class="form-text text-muted">登录 MQTT 时用的密码。长度 8-32。如果你更新了密码，那么设备将会被断开连接。</span>
        </div>

        <div class="form-group mt-1">
            <label for="password">强制 client id</label>
            <input type="text" class="form-control" id="client_id" name="client_id" value="{{ $device->client_id }}"
                   placeholder="client_id" autocomplete="off" autocorrect="off">
            <span class="form-text text-muted">如果设置，此设备必须使用该 client_id 才能登录。</span>
        </div>

        <button type="submit" class="btn btn-primary mt-3">提交</button>
    </form>

    <hr/>
    <form method="POST" action="{{ route('devices.destroy', $device) }}"
          onsubmit="return confirm('此设备将断开链接并且不复存在。')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">删除</button>
    </form>

</x-app-layout>
