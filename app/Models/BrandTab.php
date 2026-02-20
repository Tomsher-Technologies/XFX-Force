<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandTab extends Model
{
    protected $fillable = [
        'brand_id',
        'name',
        'title',
        'description',
        'image',
        'status',
        'created_by',
        'updated_by',
        'sort_order',
    ];

    // Relationship with Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
