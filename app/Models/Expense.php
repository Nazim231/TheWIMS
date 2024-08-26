<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    /**
     * Columns that are mass assigned
     */
    protected $fillable = [
        'total_price'
    ];

    /**
     * Default values for fields
     */
    protected $attributes = [
        'total_price' => 0.0,
    ];

    public function items()
    {
        return $this->hasMany(ExpenseItem::class);
    }
}
