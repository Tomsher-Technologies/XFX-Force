<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    protected $table = 'product_specification';

    protected $fillable = [
        'product_id', 'specification_id', 'specification_item_id','sort_order', 'product_stock_id',
    ];
    
    public function specification()
    {
        return $this->belongsTo(Specification::class, 'specification_id');
    }

    public function specificationItem()
    {
        return $this->belongsTo(SpecificationItem::class, 'specification_item_id');
    }

    public function stock()
    {
        return $this->belongsTo(ProductStock::class, 'product_stock_id');
    }
}
