<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'product_name',
        'variation_id',
        'SKU',
        'quantity'
    ];

    public function invoice()
    {
        return $this->belongsToMany(Invoice::class);
    }
}
