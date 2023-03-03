<?php

namespace ivampiresp\Cocoa\Http;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use ivampiresp\Cocoa\Models\WorkOrder\Reply;
use ivampiresp\Cocoa\Models\WorkOrder\WorkOrder;

class ReplyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @param  WorkOrder  $work_order
     * @param  Reply  $reply
     * @return RedirectResponse
     */
    public function store(Request $request, WorkOrder $work_order, Reply $reply): RedirectResponse
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        // push to remote
        $http = Http::remote('remote')->asForm();

        $http = $http->post('work-orders/'.$work_order->id.'/replies', [
            'content' => $request->input('content'),
            'work_order_id' => $work_order->id,
            'name' => $request->user()->name,
        ]);

        if ($http->successful()) {
            return redirect()->route('work-orders.show', $work_order)->with('success', '回复已经上传，请等待同步。');
        } else {
            return redirect()->route('work-orders.show', $work_order->id)->with('error', '无法创建回复。');
        }
    }
}
