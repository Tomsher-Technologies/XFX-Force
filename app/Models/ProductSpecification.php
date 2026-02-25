<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    protected $table = 'product_specification';

    protected $fillable = [
        'product_id', 'specification_id', 'specification_item_id','sort_order',
    ];
    
    public function specification()
    {
        return $this->belongsTo(Specification::class, 'specification_id');
    }
}
