<x-app-layout>
    <h3>已经发现的客户</h3>

    <p class="mt-3">总计: {{ $count }}</p>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>邮箱</th>
            <th>发现时间</th>
            <th>更新时间</th>
            <th>操作</th>
        </tr>
        </thead>

        {{-- 表内容 --}}
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at }}</td>
                <td>{{ $user->updated_at }}</td>
                <td>
                    <a href="{{ route('users.show', $user) }}">编辑</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    {{ $users->links() }}
</x-app-layout>
