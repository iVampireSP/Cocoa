<?php

namespace ivampiresp\Cocoa\Helpers;

use Illuminate\Http\Client\PendingRequest;
use ivampiresp\Cocoa\Models\User;

trait Http
{
    protected PendingRequest $http;

    public function __construct()
    {
        $this->http = \Illuminate\Support\Facades\Http::withToken(config('cocoa.api_token'))->baseUrl(config('cocoa.url'))->acceptJson();
    }

    public function getOrCreateUser($user_id): array|User
    {
        $user = (new User)->where('id', $user_id)->first();
        // if user null
        if (!$user) {
            $user = $this->http->get('/users/' . $user_id)->json();

            (new User)->create([
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
            ]);
        }

        return $user;
    }
}
