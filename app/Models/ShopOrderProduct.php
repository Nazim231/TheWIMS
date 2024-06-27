<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'variation_id',
        'requested_quantity',
        'approved_quantity'
    ];

    public function order()
    {
        return $this->belongsTo(ShopOrder::class, 'order_id');
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }
}
