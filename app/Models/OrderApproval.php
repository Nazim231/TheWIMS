<?php

namespace App\Models;

use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_product_id',
        'variation_id',
        'quantity',
    ];

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    public function order() 
    {
        return $this->belongsTo(ShopOrderProduct::class, 'order_product_id');
    }
}
