<?php

declare(strict_types=1);

namespace App\Shared\Helpers;

use Illuminate\Http\JsonResponse;

class JsonResponseBuilder
{
    public static function buildSuccessfullyResponse(
        int $statusCode,
        string $message,
        mixed $data,
    ): JsonResponse {
        $formattedResponse = [
            "success" => true,
            "message" => $message,
        ];

        $data !== null && $formattedResponse["data"] = $data;

        return response()->json($formattedResponse, $statusCode);
    }
}
