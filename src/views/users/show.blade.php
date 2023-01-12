<x-app-layout>
    <h3>{{ $user->name }}</h3>

    <p>发现时间: {{ $user->created_at }}</p>

    <p>邮箱: {{ $user->email }}</p>

    {{--  hosts  --}}
    <h3>主机列表</h3>
    <table class="table table-hover">
        <thead>
        <th>ID</th>
        <th>名称</th>
        <th>价格 / 月</th>
        <th>操作</th>
        </thead>
        <tbody>

        @foreach ($hosts as $host)
            <tr>
                <td>
                    {{ $host->id }}
                </td>
                <td>{{ $host->name }}</td>
                <td>
                    <span>{{ $host->getPrice() }} 元</span>
                </td>
                <td>
                    <a href="{{ route('hosts.edit', $host) }}" class="btn btn-primary btn-sm">查看</a>
                </td>
            </tr>
        </tbody>
        @endforeach
    </table>
    {{ $hosts->links() }}

    {{--  Work Orders  --}}
    <h3>工单列表</h3>
    <table class="table table-hover">
        <thead>
        <th>ID</th>
        <th>标题</th>
        <th>状态</th>
        <th>操作</th>
        </thead>
        <tbody>
        @foreach ($workOrders as $workOrder)
            <tr>
                <td>{{ $workOrder->id }}</td>
                <td>{{ $workOrder->title }}</td>
                <td>
                    <x-work-order-status :status="$workOrder->status"/>
                </td>
                <td>
                    <a href="{{ route('work-orders.show', $workOrder) }}"
                       class="btn btn-primary btn-sm">编辑</a>
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>
    {{ $workOrders->links() }}
</x-app-layout>
