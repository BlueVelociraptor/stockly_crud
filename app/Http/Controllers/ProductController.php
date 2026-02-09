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
}
