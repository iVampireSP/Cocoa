<?php

namespace ivampiresp\Cocoa\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use ivampiresp\Cocoa\Models\User;

class RemoteRequest
{
    /**
     * Handle an incoming request.
     *
     * @param Request                                       $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     *
     * @return Response|JsonResponse|RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse|RedirectResponse
    {

        // add json header
        $request->headers->set('Accept', 'application/json');
        if (!$request->hasHeader('X-Module-Api-Token')) {
            return $this->unauthorized();
        }

        $token = $request->header('X-Module-Api-Token');
        if ($token !== config('cocoa.api_token')) {
            return $this->unauthorized();
        }

        // if header has X-User-Id
        if ($request->header('X-User-Id')) {
            $user = (new User)->where('id', $request->header('X-User-Id'))->first();
            // if user null
            if (!$user) {
                $http = Http::remote()->asForm();
                $user = $http->get('/users/' . $request->header('X-User-Id'))->json();

                $user = (new User)->create([
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                ]);
            }

            Auth::guard('api')->login($user);
        }

        return $next($request);
    }

    public function unauthorized(): JsonResponse
    {
        return response()->json([
            'message' => 'Unauthorized.'
        ], 401);
    }
}
