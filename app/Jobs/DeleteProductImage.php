<?php

namespace App\Jobs;

use App\Shared\Cloudinary\Services\CloudinaryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteProductImage implements ShouldQueue
{
    use Queueable, Dispatchable, SerializesModels, InteractsWithQueue;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly string $imagePublicId
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        CloudinaryService $cloudinaryService
    ): void {
        $cloudinaryService->deleteImage($this->imagePublicId);
    }
}
