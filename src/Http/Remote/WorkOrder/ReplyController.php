<?php

namespace ivampiresp\Cocoa\Http\Remote\WorkOrder;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ivampiresp\Cocoa\Http\Controller;
use ivampiresp\Cocoa\Models\WorkOrder\Reply;
use ivampiresp\Cocoa\Models\WorkOrder\WorkOrder;

class ReplyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $reply = Reply::create($request->all());

        return $this->created($reply);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  WorkOrder  $work_order
     * @param  Reply  $reply
     * @return JsonResponse
     */
    public function update(Request $request, WorkOrder $work_order, Reply $reply): JsonResponse
    {
        $reply->update($request->all());

        return $this->updated($reply);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  WorkOrder  $work_order
     * @param  Reply  $reply
     * @return JsonResponse
     */
    public function destroy(WorkOrder $work_order, Reply $reply): JsonResponse
    {
        $reply->delete();

        return $this->deleted();
    }
}
