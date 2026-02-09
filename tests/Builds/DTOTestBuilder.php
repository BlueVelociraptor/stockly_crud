<?php

declare(strict_types=1);

namespace Tests\Builds;

use App\Shared\Data\BaseDTO;

class DTOTestBuilder extends BaseDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $age,
    ) {}

    public function getAgeProperty(): string
    {
        return "age";
    }
}
