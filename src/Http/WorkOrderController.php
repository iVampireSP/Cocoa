<?php

namespace ivampiresp\Cocoa\Http;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use ivampiresp\Cocoa\Models\WorkOrder\WorkOrder;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return View
     */
    public function index(Request $request): View
    {
        //
        $workOrders = WorkOrder::with('user');

        $workOrders = $workOrders->where('status', $request->status ?? 'open');

        $workOrders = $workOrders->simplePaginate(10);

        return view('Cocoa::workOrders.index', compact('workOrders'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  WorkOrder  $work_order
     * @return RedirectResponse|View
     */
    public function show(Request $request, WorkOrder $work_order): View|RedirectResponse
    {
        $request->validate([
            'status' => 'sometimes|in:closed,on_hold,in_progress',
        ]);

        $http = Http::remote('remote')->asForm();

        if ($request->filled('status')) {
            $http = $http->patch('work-orders/'.$work_order->id, [
                'status' => $request->status,
            ]);

            if ($request->has('status')) {
                return back()->with('success', '工单状态已更新，请等待同步。');
            }
        } else {
            // if work order status is open or user_replied, then set to read
            if ($work_order->status == 'open' || $work_order->status == 'user_replied') {
                $http = $http->patch('work-orders/'.$work_order->id, [
                    'status' => 'read',
                ]);
            }
        }

        $work_order->load(['replies', 'user', 'host']);

        $user = $work_order->user;

        return view('Cocoa::workOrders.show', compact('work_order', 'user'));
    }
}
