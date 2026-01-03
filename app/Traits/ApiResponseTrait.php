<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Send a success response.
     */
    protected function ok($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Send a failure response.
     */
    protected function fail(string $message = 'Error', $errors = null, int $code = 400): JsonResponse
    {
        $payload = [
            'status' => false,
            'message' => $message,
        ];

        if ($errors) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }
}
