<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductWarranty extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'price',
        'months',
        'description',
    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
