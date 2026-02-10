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

    public static function buildUnprocessableErrorResponse(
        int $statusCode,
        string $errorMessage,
        ?string $unprocessableField,
    ): JsonResponse {
        $formattedResponse = [
            "success" => false,
            "message" => $errorMessage,
        ];

        $unprocessableField !== null && $formattedResponse["errors"] = [
            "{$unprocessableField}" => [
                $errorMessage
            ]
        ];

        return response()->json($formattedResponse, $statusCode);
    }
}
