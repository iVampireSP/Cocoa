<?php

namespace ivampiresp\Cocoa\Helpers;

use Illuminate\Http\Client\PendingRequest;

trait Http
{
    protected PendingRequest $http;

    public function __construct()
    {
        $this->http = \Illuminate\Support\Facades\Http::withToken(config('cocoa.api_token'))->baseUrl(config('cocoa.url'))->acceptJson();
    }
}
