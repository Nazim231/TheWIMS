<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_name',
        'emp_name',
        'shop_address',
        'customer_name',
        'customer_mobile'
    ];

    public function products()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}
