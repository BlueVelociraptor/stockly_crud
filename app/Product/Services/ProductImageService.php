<?php

declare(strict_types=1);

namespace App\Product\Services;

use App\Models\Product;
use App\Product\Repositories\ProductImageRepository;
use App\Product\Repositories\ProductRepository;
use App\Shared\Cloudinary\Services\CloudinaryService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductImageRepository $productImageRepository,
        private readonly CloudinaryService $cloudinaryService,
    ) {}

    public function saveProductImage(string $imageSavedInDisk, int $productId): void
    {
        $product = $this->verifyProductExists($productId);

        if ($product === null) return;

        $imagePath = Storage::disk("temp")->path($imageSavedInDisk);

        $cloudinaryDto = $this->cloudinaryService->uploadImage($imagePath);
        $this->productImageRepository->createOne($product, $cloudinaryDto);

        Storage::disk("temp")->delete($imagePath);
    }

    public function verifyProductExists(int $productId): ?Product
    {
        $product = $this->productRepository->findOneById($productId);

        if ($product === null) Log::info("Image isn't uploading since product doesn't exist!");

        return $product;
    }
}
