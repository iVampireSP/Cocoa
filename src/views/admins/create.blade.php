@section('title', '新建管理员')
<x-app-layout>
    <h3>权力越大，责任越大</h3>
    <a class="mt-3" href="{{ route('admins.index') }}">返回管理员列表</a>

    <form method="POST" action="{{ route('admins.store') }}">
        @csrf

        <div class="form-group mt-1">
            <label for="name">名称</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="名称" required>
        </div>

        <div class="form-group mt-1">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">提交</button>
    </form>

</x-app-layout>
