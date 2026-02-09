<?php

declare(strict_types=1);

namespace App\Product\Data;

use App\Shared\Data\BaseDTO;
use Illuminate\Http\UploadedFile;

class SaveProductDTO extends BaseDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $price,
        public readonly UploadedFile $image,
        public readonly ?string $description,
    ) {}

    public function getImageProperty(): string
    {
        return "image";
    }
}
