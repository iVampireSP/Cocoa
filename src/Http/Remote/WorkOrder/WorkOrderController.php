<?php

namespace ivampiresp\Cocoa\Http\Remote\WorkOrder;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use ivampiresp\Cocoa\Http\Controller;
use ivampiresp\Cocoa\Models\User;
use ivampiresp\Cocoa\Models\WorkOrder\WorkOrder;

class WorkOrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $this->getOrCreateUser($request->input('user_id'));

        $req = $request->all();

        $workOrder = WorkOrder::create($req);

        return $this->success($workOrder);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request   $request
     * @param WorkOrder $work_order
     *
     * @return JsonResponse
     */
    public function update(Request $request, WorkOrder $work_order): JsonResponse
    {
        $this->getOrCreateUser($request->input('user_id'));

        $req = $request->all();

        // if ($request->filled('host_id')) {
        //     // find host
        //     $host = Host::where('host_id', $request->host_id)->firstOrFail();

        //     $req['host_id'] = $host->id;
        // }

        $work_order->update($req);

        return $this->updated($work_order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param WorkOrder $work_order
     *
     * @return JsonResponse
     */
    public function destroy(WorkOrder $work_order): JsonResponse
    {
        $work_order->delete();

        return $this->deleted();
    }
}
