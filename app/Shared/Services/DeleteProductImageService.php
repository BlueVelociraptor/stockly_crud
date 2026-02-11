<?php

declare(strict_types=1);

namespace App\Shared\Services;

use App\Jobs\DeleteProductImage;

class DeleteProductImageService
{
    public function uploadJob(string $imagePublicId): void
    {
        DeleteProductImage::dispatch($imagePublicId);
    }
}
