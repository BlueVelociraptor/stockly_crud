<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SaveProductRequest;
use App\Product\Services\ProductService;
use App\Shared\Helpers\JsonResponseBuilder;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ) {}

    public function saveProductEndpoint(SaveProductRequest $request): JsonResponse
    {
        $product = $this->productService->saveProduct($request->toDTO());

        return JsonResponseBuilder::buildSuccessfullyResponse(
            statusCode: 201,
            message: "You have created a new Product successfully!",
            data: $product,
        );
    }

    public function getAllProductEndpoint(): JsonResponse
    {
        $products = $this->productService->getAllProducts();

        return JsonResponseBuilder::buildSuccessfullyResponse(
            statusCode: 200,
            message: "You have gotten your products successfully!",
            data: $products
        );
    }

    public function getProductByIdEndpoint(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        return JsonResponseBuilder::buildSuccessfullyResponse(
            statusCode: 200,
            message: "You have gotten your product successfully!",
            data: $product,
        );
    }

    public function updateProductStatusEndpoint(int $id): JsonResponse
    {
        $product = $this->productService->updateProductStatus($id);

        return JsonResponseBuilder::buildSuccessfullyResponse(
            statusCode: 200,
            message: "You have updated your product status successfully!",
            data: $product,
        );
    }

    public function deleteProductEndpoint(int $id): JsonResponse
    {
        $this->productService->deleteProduct($id);

        return JsonResponseBuilder::buildSuccessfullyResponse(
            statusCode: 200,
            message: "You have deleted the product successfully!",
            data: null,
        );
    }
}
