<?php

declare(strict_types=1);

namespace App\Product\Repositories;

use App\Models\Product;
use App\Models\Product_Image;
use App\Shared\Cloudinary\Data\ImageUploadedToCloudinaryDTO;

class ProductImageRepository
{
    public function createOne(Product $product, ImageUploadedToCloudinaryDTO $cloudinaryDto): Product_Image
    {
        return $product->product_image()->firstOrCreate([
            "public_url" => $cloudinaryDto->public_url,
            "public_id" => $cloudinaryDto->public_id,
        ], $cloudinaryDto->toArray(
            excludedProperties: []
        ));
    }
}
