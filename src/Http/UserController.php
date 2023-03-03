<?php

namespace ivampiresp\Cocoa\Http;

use Illuminate\Http\Request;
use Illuminate\View\View;
use ivampiresp\Cocoa\Models\Host;
use ivampiresp\Cocoa\Models\User;
use ivampiresp\Cocoa\Models\WorkOrder\WorkOrder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return View
     */
    public function index(Request $request): View
    {
        $users = new User();

        foreach ($request->except(['page']) as $key => $value) {
            if (empty($value)) {
                continue;
            }
            if ($request->{$key}) {
                $users = $users->where($key, 'LIKE', '%'.$value.'%');
            }
        }

        $count = $users->count();

        $users = $users->paginate(100);

        return view('Cocoa::users.index', ['users' => $users, 'count' => $count]);
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return View
     */
    public function show(User $user): View
    {
        $hosts = Host::where('user_id', $user->id)->latest()->paginate(50, ['*'], 'hosts_page');
        $workOrders = WorkOrder::where('user_id', $user->id)->latest()->paginate(50, ['*'], 'workOrders_page');

        return view('Cocoa::users.show', compact('hosts', 'workOrders', 'user'));
    }
}
