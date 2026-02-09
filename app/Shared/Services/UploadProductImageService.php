<?php

declare(strict_types=1);

namespace App\Shared\Services;

use App\Jobs\UploadProductImage;

class UploadProductImageService
{
    public function dispatchJob(string $imageSavedInDisk, int $productId): void
    {
        UploadProductImage::dispatch(
            $imageSavedInDisk,
            $productId,
        );
    }
}
