<?php

declare(strict_types=1);

namespace App\Shared\Cloudinary\Data;

use App\Shared\Data\BaseDTO;

class ImageUploadedToCloudinaryDTO extends BaseDTO
{
    public function __construct(
        public readonly string $public_url,
        public readonly string $public_id,
    ) {}
}
