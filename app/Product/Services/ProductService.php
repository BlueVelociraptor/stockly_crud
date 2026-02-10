<?php

declare(strict_types=1);

namespace App\Product\Services;

use App\Models\Product;
use App\Product\Data\SaveProductDTO;
use App\Product\Exceptions\ProductDoesntExistException;
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


    public function verifyProductExistsById(int $id): Product
    {
        $product = $this->productRepository->findOneById($id);

        if ($product === null) throw new ProductDoesntExistException();

        return $product;
    }
}
