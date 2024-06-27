<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'emp_id',
    ];

    public function shopOwner()
    {
        return $this->belongsTo(User::class, 'emp_id', 'id');
    }

    public function stock()
    {
        return $this->hasMany(ShopsStock::class);
    }
}
