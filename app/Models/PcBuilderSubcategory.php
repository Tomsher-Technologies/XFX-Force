<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcBuilderSubcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'builder_category_id',
        'subcategory_id'
    ];

    public function pcBuilderCategorySetting()
    {
        return $this->belongsTo(PcBuilderCategorySetting::class, 'builder_category_id');
    }
    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }
}
