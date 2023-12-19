<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function response(mixed $data, int $responseCode = 200): JsonResponse
    {
        $success = $data['success'] ?? true;
        $message = $data['message'] ?? 'Success.';
        unset($data['success'], $data['message']);

        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $data['data'] ?? $data
        ];

        return response()->json($response, $responseCode);
    }
}
