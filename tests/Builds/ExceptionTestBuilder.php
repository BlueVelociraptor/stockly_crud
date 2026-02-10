<?php

declare(strict_types=1);

namespace Tests\Builds;

use App\Shared\Exceptions\BaseException;

class ExceptionTestBuilder extends BaseException
{
    public function __construct(
        string $message,
        int $statusCode,
        ?string $unprocessableField,
    ) {
        return parent::__construct($message, $statusCode, $unprocessableField);
    }
}
