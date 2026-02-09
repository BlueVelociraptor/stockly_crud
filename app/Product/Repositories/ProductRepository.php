<?php

declare(strict_types=1);

namespace App\Product\Repositories;

use App\Models\Product;
use App\Product\Data\SaveProductDTO;

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
        return Product::whereId($id)->first();
    }
}
