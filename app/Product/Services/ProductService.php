<?php

declare(strict_types=1);

namespace App\Product\Services;

use App\Models\Product;
use App\Product\Data\SaveProductDTO;
use App\Product\Exceptions\ProductDoesntExistException;
use App\Product\Repositories\ProductRepository;
use App\Shared\Services\DeleteProductImageService;
use App\Shared\Services\UploadProductImageService;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly UploadProductImageService $uploadProductImageService,
        private readonly DeleteProductImageService $deleteProductImageService,
    ) {}

    public function saveProduct(SaveProductDTO $dto): Product
    {
        $product = $this->productRepository->createOne($dto);
        $imageSavedInDisk = $dto->image->store("products", "temp");
        $this->uploadProductImageService->dispatchJob($imageSavedInDisk, $product->id);

        return $product;
    }

    public function getAllProducts(): array
    {
        $paginator = $this->productRepository->findAll();

        return [
            "products" => $paginator->items(),
            "total_pages" => $paginator->total(),
            "per_page" => $paginator->perPage(),
            "current_page" => $paginator->currentPage(),
            "last_page" => $paginator->lastPage(),
        ];
    }

    public function getProductById(int $id): Product
    {
        return $this->verifyProductExistsById($id);
    }

    //TODO: Test
    public function deleteProduct(int $id): bool
    {
        $product = $this->verifyProductExistsById($id);
        $this->deleteProductImageService->uploadJob($product->product_image->public_id);

        return $this->productRepository->deleteOne($product);
    }

    public function updateProductStatus(int $id): Product
    {
        $product = $this->verifyProductExistsById($id);
        return $this->productRepository->updateStatus($product);
    }


    public function verifyProductExistsById(int $id): Product
    {
        $product = $this->productRepository->findOneById($id);

        if ($product === null) throw new ProductDoesntExistException();

        return $product;
    }
}
