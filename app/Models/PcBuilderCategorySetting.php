<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcBuilderCategorySetting extends Model
{
    protected $fillable = [
        'category_id',
        'min_select',
        'max_select',
        'has_subcategories',
        'sort_order',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getIsMandatoryAttribute()
    {
        return $this->min_select > 0;
    }

    public function getIsUnlimitedAttribute()
    {
        return is_null($this->max_select);
    }

    public function subcategories()
    {
        return $this->belongsToMany(
            Category::class,
            'pc_builder_subcategories',
            'builder_category_id',
            'subcategory_id'
        );
    }

}

