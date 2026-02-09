<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property string $public_url
 * @property string $public_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_Image query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_Image whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_Image wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_Image wherePublicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_Image whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product_Image extends Model
{
    protected $table = "product_image";

    protected $fillable = [
        "public_url",
        "public_id",
    ];

    protected $guarded = [
        "id",
        "product_id",
        "created_at",
        "updated_at",
    ];

    protected $casts = [
        "id" => "integer",
        "product_id" => "integer",
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id", "id");
    }
}
