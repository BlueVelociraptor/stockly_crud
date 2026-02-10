<?php

declare(strict_types=1);

namespace App\Product\Exceptions;

use App\Shared\Exceptions\BaseException;


class ProductDoesntExistException extends BaseException
{
    public function __construct(
        string $message = "We couldn't find a product registered with the ID you provided!",
        int $statusCode = 404,
        ?string $unprocessableField = null,
    ) {
        return parent::__construct($message, $statusCode, $unprocessableField);
    }
}
