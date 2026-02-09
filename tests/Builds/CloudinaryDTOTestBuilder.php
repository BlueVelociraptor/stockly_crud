<?php

declare(strict_types=1);

namespace Tests\Builds;

use App\Shared\Cloudinary\Data\ImageUploadedToCloudinaryDTO;

class CloudinaryDTOTestBuilder
{
    public static function buildCloudinaryDTOTestSubject(): ImageUploadedToCloudinaryDTO
    {
        return new ImageUploadedToCloudinaryDTO(
            public_url: "http://example.com",
            public_id: "12345",
        );
    }
}
