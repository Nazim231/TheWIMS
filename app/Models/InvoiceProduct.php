<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;

class InvoiceProduct extends Model
{
    use HasFactory;
    use BelongsToThrough;

    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'product_name',
        'variation_id',
        'SKU',
        'quantity',
        'price',
        'total_price',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    public function product()
    {
        return $this->belongsToThrough(Product::class, ProductVariation::class, null, '', [
            ProductVariation::class => 'variation_id',
            Product::class => 'product_id'
        ]);
    }
}
