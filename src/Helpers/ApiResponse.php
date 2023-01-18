<?php

namespace ivampiresp\Cocoa\Helpers;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function notFound($message = 'Not found'): JsonResponse
    {
        return $this->error($message, 404);
    }

    public function error($message = '', $code = 400): JsonResponse
    {
        return $this->apiResponse(['message' => $message], $code);
    }

    public function apiResponse($data, $status = 200): JsonResponse
    {
        if (is_string($data)) {
            $data = ['message' => $data];
        }

        return response()->json($data, $status);
    }

    public function forbidden($message = 'Forbidden'): JsonResponse
    {
        return $this->error($message, 403);
    }

    public function unauthorized($message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, 401);
    }

    public function badRequest($message = 'Bad request'): JsonResponse
    {
        return $this->error($message);
    }

    public function created($message = 'Created'): JsonResponse
    {
        return $this->success($message);
    }

    public function success($data = []): JsonResponse
    {
        return $this->apiResponse($data);
    }

    public function failed($message = 'Failed'): JsonResponse
    {
        return $this->error($message);
    }

    public function accepted($message = 'Accepted'): JsonResponse
    {
        return $this->success($message, 202);
    }

    public function noContent($message = 'No content'): JsonResponse
    {
        return $this->success($message, 204);
    }

    public function updated($message = 'Updated'): JsonResponse
    {
        return $this->success($message, 200);
    }

    public function deleted($message = 'Deleted'): JsonResponse
    {
        return $this->success($message, 200);
    }

    public function notAllowed($message = 'Not allowed'): JsonResponse
    {
        return $this->error($message, 405);
    }

    public function conflict($message = 'Conflict'): JsonResponse
    {
        return $this->error($message, 409);
    }

    public function tooManyRequests($message = 'Too many requests'): JsonResponse
    {
        return $this->error($message, 429);
    }

    public function serverError($message = 'Server error'): JsonResponse
    {
        return $this->error($message, 500);
    }

    public function serviceUnavailable($message = 'Service unavailable'): JsonResponse
    {
        return $this->error($message, 503);
    }

    public function methodNotAllowed($message = 'Method not allowed'): JsonResponse
    {
        return $this->error($message, 405);
    }

    public function notAcceptable($message = 'Not acceptable'): JsonResponse
    {
        return $this->error($message, 406);
    }

    public function preconditionFailed($message = 'Precondition failed'): JsonResponse
    {
        return $this->error($message, 412);
    }
}
