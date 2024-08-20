<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'mrp',
        'weight',
        'height',
        'width',
        'length',
        'color',
        'size',
        'quantity',
        'cost_price',
    ];

    /**
     * Get the product that owns this variation
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
