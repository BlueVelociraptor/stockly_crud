<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Product\Services\ProductImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadProductImage implements ShouldQueue
{
    use Queueable, SerializesModels, InteractsWithQueue, Dispatchable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly string $imageSavedInDisk,
        public readonly int $productId,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        ProductImageService $productImageService,
    ): void {
        $productImageService->saveProductImage($this->imageSavedInDisk, $this->productId);
    }
}
