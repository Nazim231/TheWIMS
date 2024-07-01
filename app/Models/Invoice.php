<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'emp_name',
        'shop_id',
        'shop_name',
        'shop_address',
        'customer_id',
        'customer_name',
        'customer_mobile',
        'total_amount',
    ];

    public function products()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}
