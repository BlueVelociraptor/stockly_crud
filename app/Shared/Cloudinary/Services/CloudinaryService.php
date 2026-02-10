<?php

declare(strict_types=1);

namespace App\Shared\Cloudinary\Services;

use App\Shared\Cloudinary\Data\ImageUploadedToCloudinaryDTO;

class CloudinaryService
{
    public function uploadImage(string $imagePath): ImageUploadedToCloudinaryDTO
    {
        $cloudinaryResponse = cloudinary()->uploadApi()->upload($imagePath, [
            "folder" => env("CLOUDINARY_PRODUCTS_FOLDER"),
            "overwrite" => true,
            "invalidate" => true,
        ]);

        return new ImageUploadedToCloudinaryDTO(
            public_url: $cloudinaryResponse["secure_url"],
            public_id: $cloudinaryResponse["public_id"],
        );
    }

    public function deleteImage(string $publicId): void
    {
        cloudinary()->uploadApi()->destroy($publicId . [
            "invalidate" => true,
        ]);
    }
}
