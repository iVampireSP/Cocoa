<x-app-layout>
    <h1>管理员登录</h1>

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email" class="text-left ml-0">邮箱</label>
            <input type="email" name="email" id="email" class="form-control mb-3" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">密码</label>
            <input type="password" id="password" name="password"
                   class="form-control rounded-right" required>
        </div>

        <div class="form-group mt-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" checked>
                <label class="form-check-label" for="remember">
                    记住登录
                </label>
            </div>
        </div>

        <button class="btn btn-primary btn-block mt-3" type="submit">
            登录
        </button>
    </form>

</x-app-layout>
