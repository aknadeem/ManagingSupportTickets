<?php

namespace App\Traits;

trait ApiResponses
{
    protected function responseOk($message): \Illuminate\Http\JsonResponse
    {
        return $this->responseSuccess($message, 200);
    }
    protected function responseSuccess($message, $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}
