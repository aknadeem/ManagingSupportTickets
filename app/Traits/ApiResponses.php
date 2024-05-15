<?php

namespace App\Traits;

trait ApiResponses
{
    protected function responseOk($message, $data): \Illuminate\Http\JsonResponse
    {
        return $this->responseSuccess($message, $data,  200);
    }
    protected function responseSuccess($message,$data, $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    protected function responseError($message, $statusCode): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}
