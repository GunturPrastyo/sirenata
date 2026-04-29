<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function success(
        bool   $status,
        string $message,
        mixed  $result,
        int    $statusCode = 200,
        mixed  $auth = null
    ): JsonResponse {
        $response = [
            'status'  => $status,
            'message' => $message,
            'result'  => $result,
        ];

        if ($auth !== null) {
            $response['auth'] = $auth;
        }

        return response()->json($response, $statusCode);
    }

    public static function error($message, $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}
