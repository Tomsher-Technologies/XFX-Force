<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use URL;

class ProductStock extends Model
{
    // protected $fillable = ['product_id', 'qty', 'price', 'description'];
    protected $fillable = [
    'product_id', 'type', 'sku', 'qty', 'vat', 'status', 'price', 'offer_price', 'offer_tag', 'stock_description', 'image'
];

    //
    public function product(){
    	return $this->belongsTo(Product::class);
    }

    public function wholesalePrices() {
        return $this->hasMany(WholesalePrice::class);
    }

    public function image($path)
    {
        return URL::to($path);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttributes::class, 'product_varient_id');
    }
}
