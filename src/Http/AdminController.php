<?php

namespace ivampiresp\Cocoa\Http;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use ivampiresp\Cocoa\Models\Admin;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $admins = Admin::paginate(100);

        return view('Cocoa::admins.index', compact('admins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        //

        $request->validate([
            'email' => 'required|email|unique:admins,email',
            'name' => 'required|string|max:20',
        ]);

        // 随机密码
        $password = Str::random();

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
        ]);

        return redirect()->route('Cocoa::admins.edit', $admin)->with('success', '管理员创建成功，密码为：'.$password.'。');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('Cocoa::admins.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Admin  $admin
     * @return View
     */
    public function edit(Admin $admin): View
    {
        return view('Cocoa::admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Admin  $admin
     * @return RedirectResponse
     */
    public function update(Request $request, Admin $admin): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|unique:admins,email,'.$admin->id,
            'name' => 'required|string|max:20',
        ]);

        $msg = '管理员信息更新成功';

        if ($request->filled('reset_password')) {
            // 随机密码
            $password = Str::random();

            $msg .= '，新的密码为：'.$password;

            $admin->password = bcrypt($password);
        }

        $msg .= '。';

        $admin->name = $request->name;
        $admin->email = $request->email;

        $admin->save();

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Admin  $admin
     * @return RedirectResponse
     */
    public function destroy(Admin $admin): RedirectResponse
    {
        // 不能删除自己
        if ($admin->id == auth('admin')->id()) {
            return redirect()->back()->with('error', '不能删除自己。');
        }

        // 不能删除最后一个管理员
        if (Admin::count() == 1) {
            return redirect()->back()->with('error', '不能删除最后一个管理员。');
        }

        $admin->delete();

        return redirect()->route('admins.index')->with('success', '管理员已删除。');
    }
}
