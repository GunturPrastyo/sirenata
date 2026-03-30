<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function success($status, $message, $result, $statusCode): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'result' => $result,
        ], $statusCode);
}

    public static function error($message, $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}
