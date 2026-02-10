<?php

declare(strict_types=1);

namespace App\Shared\Services;

use App\Jobs\UploadProductImage;

class DeleteProductImageService
{
    public function uploadJob(string $imagePublicId): void
    {
        UploadProductImage::dispatch($imagePublicId);
    }
}
