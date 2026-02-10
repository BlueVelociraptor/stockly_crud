<?php

declare(strict_types=1);

namespace App\Product\Repositories;

use App\Models\Product;
use App\Product\Data\SaveProductDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function createOne(SaveProductDTO $dto): Product
    {
        return Product::create($dto->toArray(
            excludedProperties: [$dto->getImageProperty()]
        ));
    }

    public function findOneById(int $id): ?Product
    {
        return Product::whereId($id)->with("product_image")->first();
    }

    public function findAll(): LengthAwarePaginator
    {
        return Product::with("product_image")->paginate(9);
    }
}
