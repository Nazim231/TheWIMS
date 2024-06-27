<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;

class ShopsStock extends Model
{
    use HasFactory, BelongsToThrough;

    protected $table = 'shops_stock';

    public $timestamps = true;

    protected $fillable = [
        'shop_id',
        'variation_id',
        'quantity',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function product()
    {
        return $this->belongsToThrough(Product::class, ProductVariation::class, null, '', [
            ProductVariation::class => 'variation_id',
            Product::class => 'product_id',
        ]);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
}
