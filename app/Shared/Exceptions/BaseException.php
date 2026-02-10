<?php

declare(strict_types=1);

namespace App\Shared\Exceptions;

use App\Shared\Helpers\JsonResponseBuilder;
use Exception;
use Illuminate\Http\JsonResponse;


abstract class BaseException extends Exception
{
    private readonly int $statusCode;
    private readonly ?string $unprocessableField;

    public function __construct(
        string $message,
        int $statusCode,
        ?string $unprocessableField,
    ) {
        $this->statusCode = $statusCode;
        $this->unprocessableField = $unprocessableField;

        return parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return JsonResponseBuilder::buildUnprocessableErrorResponse(
            statusCode: $this->statusCode,
            errorMessage: $this->getMessage(),
            unprocessableField: $this->unprocessableField,
        );
    }
}
