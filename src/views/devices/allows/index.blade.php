@section('title', '设备授权')

<x-app-layout>
    <h3>授权</h3>
    <p>允许或拒绝此设备的订阅或者发布行为。将会按照顺序进行判断。</p>
    <a href="{{ route('devices.edit', $device) }}">编辑设备</a>

    <div class="overflow-auto mt-3">
        <table class="table table-hover">
            <thead>
            <th>类型</th>
            <th>主题</th>
            <th>行动</th>
            <th>操作</th>
            </thead>

            <tbody>
            @foreach ($allows as $allow)
                {{-- {{ dd($device) }} --}}
                <tr>
                    <td>
                        @if ($allow->type === 'publish')
                            发布
                        @else
                            订阅
                        @endif
                    </td>
                    <td>
                        {{ $allow->topic }}
                    </td>
                    <td>
                        @if ($allow->action === 'allow')
                            <span class="text-success">允许</span>
                        @else
                            <span class="text-danger">拒绝</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('devices.allows.destroy', $allow) }}">
                            @method('delete')
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">删除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <form action="{{ route('devices.allows.store', $device) }}" method="POST">
        @csrf
        {{-- 类型 --}}
        <div class="form-group">
            <label for="type">类型</label>
            <select name="type" id="type" class="form-control">
                <option value="publish">发布</option>
                <option value="subscribe">订阅</option>
            </select>

            @error('type')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="action">行动</label>
            <select name="action" id="action" class="form-control">
                <option value="allow">允许</option>
                <option value="deny">拒绝</option>
            </select>

            @error('action')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- 主题 --}}
        <div class="form-group mt-1">
            <label for="topic">主题</label>
            <input type="text" name="topic" id="topic" class="form-control"
                   placeholder="{{ config('remote.module_id') }}/example" value="{{ old('topic') }}">

            @error('topic')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- 提交 --}}
        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">提交</button>
        </div>

    </form>
</x-app-layout>
