<?php

declare(strict_types=1);

namespace App\Product\Services;

use App\Models\Product;
use App\Product\Data\SaveProductDTO;
use App\Product\Repositories\ProductRepository;
use App\Shared\Services\UploadProductImageService;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly UploadProductImageService $uploadProductImageService,
    ) {}

    public function saveProduct(SaveProductDTO $dto): Product
    {
        $product = $this->productRepository->createOne($dto);
        $imageSavedInDisk = $dto->image->store("products", "temp");
        $this->uploadProductImageService->dispatchJob($imageSavedInDisk, $product->id);

        return $product;
    }
}
