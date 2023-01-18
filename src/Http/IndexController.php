<?php

namespace ivampiresp\Cocoa\Http;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class IndexController extends Controller
{
    //

    public function index(Request $request): View|RedirectResponse
    {
        if ($request->filled('fast_login_token')) {
            $admin = Cache::get('fast_login_' . $request->input('fast_login_token'));

            if ($admin) {
                Auth::guard('admin')->login($admin, true);

                Cache::forget('fast_login_' . $request->input('fast_login_token'));

                return redirect()->route('index')->with('success', '您已从 莱云 面板登录。');
            } else {
                // 丢弃所有 session
                Auth::guard('admin')->logout();

                return redirect()->route('login')->with('error', '您需要登录才能继续。');
            }
        }

        // if not login, redirect to log in
        if (!Auth::guard('admin')->check()) {
            return view('Cocoa::login');
        } else {
            $modules = $this->http->get('modules');

            $response = $modules->json();
            if ($modules->successful()) {
                return view('Cocoa::index', ['years' => $response]);
            } else {
                return view('Cocoa::error', ['response' => $response]);
            }
        }
    }

    public function login(Request $request): RedirectResponse
    {
        // attempt to login
        if (Auth::guard('admin')->attempt($request->only(['email', 'password']), $request->has('remember'))) {
            // if success, redirect to home
            return redirect()->intended();
        } else {
            // if failed, redirect to log in with error message
            return redirect()->back()->withErrors(['message' => '用户名或密码错误'])->withInput();
        }
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('admin')->logout();
        Session::flush();

        return redirect()->route('login');
    }
}
