<?php

namespace ivampiresp\Cocoa\Http;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use ivampiresp\Cocoa\Helpers\ApiResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponse;

    protected $http;

    public function __construct()
    {
        $this->http = Http::remote()->asForm();
    }
}
