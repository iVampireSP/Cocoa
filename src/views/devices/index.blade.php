@section('title', '设备列表')

<x-app-layout>
    <h3>设备</h3>
    <p>设备可以用来连接到 莱云 的 MQTT 网络。</p>
    <a href="{{ route('devices.create') }}">新设备</a>
    <div class="overflow-auto">
        <table class="table table-hover">
            <thead>
            <th>名称</th>
            <th>Client ID</th>
            <th>操作</th>
            </thead>

            <tbody>
            @foreach ($devices as $device)
                {{-- {{ dd($device) }} --}}
                <tr>
                    <td>
                        <a href="{{ route('devices.edit', $device) }}">
                            {{ $device->name }}
                        </a>
                    </td>
                    <td>
                        @if ($device->client_id)
                            {{ $device->client_id }}
                        @else
                            无
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('devices.allows.index', $device) }}" class="btn btn-primary btn-sm">授权</a>
                        <a href="{{ route('devices.edit', $device) }}" class="btn btn-primary btn-sm">编辑</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- 分页 --}}
    {{ $devices->links() }}
</x-app-layout>
