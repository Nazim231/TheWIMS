<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Mass assignable columns
     */
    protected $fillable = [
        'name',
        'description',
        'brand_id',
        'category_id'
    ];

    /**
     * Default values for the columns
     */
    protected $attributes = [
        'description' => null,
    ];

    /**
     * Get variants of product
     */
    public function variants()
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Get category of product
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get brand of product
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
