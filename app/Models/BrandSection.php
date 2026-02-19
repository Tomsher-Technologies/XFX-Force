<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandSection extends Model
{
    protected $fillable = [
        'brand_id',
        'title',
        'description',
        'image',
        'status',
        'created_by',
        'updated_by'
    ];

    // Relationship with Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
