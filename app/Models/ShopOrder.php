<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;

class ShopOrder extends Model
{
    use HasFactory, BelongsToThrough;

    protected $fillable = [
        'shop_id',
        'status',
    ];

    public function shop() {
        return $this->belongsTo(Shop::class);
    }

    public function products() {
        return $this->hasMany(ShopOrderProduct::class, 'order_id');
    }
}
