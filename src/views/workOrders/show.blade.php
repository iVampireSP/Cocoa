<x-app-layout>
    <a href="?status=on_hold">挂起此工单</a>
    <a href="?status=in_progress">标记为处理中</a>
    <a href="?status=closed">关闭工单</a>

    <h3>{{ $work_order->title }}</h3>
    客户: {{ $work_order->user->name }} 的工单

    <h5>@parsedown($work_order->content)</h5>

    <x-work-order-status :status="$work_order->status"></x-work-order-status>


    @if ($work_order->host_id)
        服务 页面 <a href=" {{ route('hosts.show', $work_order->host->host_id) }}">{{ $work_order->host->name }}</a>
    @endif


    <div class="mt-3">
        <h4>对话记录</h4>

        @foreach ($work_order->replies as $reply)
            <div class="card border-light mb-3 shadow">
                <div class="card-header d-flex w-100 justify-content-between">
                    @if ($reply->role === 'user')
                        @if ($reply->user_id)
                            <a href="{{ route('users.show', $reply->user) }}">{{ $work_order->user->name }}</a>
                        @else
                            <span>{{ $reply->name }}</span>
                        @endif
                    @elseif ($reply->role === 'admin')
                        <span class="text-primary"> 莱云 </span>
                    @elseif ($reply->role === 'module')
                        模块: {{ $reply->name }}
                    @elseif ($reply->role === 'guest')
                        {{ $reply->name }}
                    @endif

                    <span class="text-end">{{ $reply->created_at }}</span>
                </div>
                <div class="card-body">
                    @parsedown($reply->content)
                </div>
            </div>
        @endforeach
    </div>


    <h2>您的回复</h2>
    <form method="POST" action="{{ route('work-orders.replies.store', $work_order->id) }}">
        @csrf
        <textarea class="form-control" name="content" placeholder="您的回复" rows="10"></textarea>


        <button type="submit" class="btn btn-primary mt-3 mb-3">提交</button>
    </form>

    <a href="?status=on_hold">挂起此工单</a>
    <a href="?status=in_progress">标记为处理中</a>
    <a href="?status=closed">关闭工单</a>

</x-app-layout>
