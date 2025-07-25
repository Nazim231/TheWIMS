<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    protected $attributes = [
        'parent_category_id' => null
    ];

    /**
     * Get product of Category
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
