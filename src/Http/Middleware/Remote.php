<?php

namespace ivampiresp\Cocoa\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Remote
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
        if ($token !== config('remote.api_token')) {
            return $this->unauthorized();
        }
        //

        if ($request->user_id) {
            $user = User::where('id', $request->user_id)->first();
            // if user null
            if (!$user) {
                $http = Http::remote('remote')->asForm();
                $user = $http->get('/users/' . $request->user_id)->json();

                $user = User::create([
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                ]);
            }

            Auth::guard('user')->login($user);
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
