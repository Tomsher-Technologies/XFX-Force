<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecificationItem extends Model
{
    protected $fillable = [
        'main_specification_id',
        'parent_id',
        'title',
        'display_title',
        'status',
        'sort_order',
    ];

    /**
     * Get the child specification items.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subItems()
    {
        return $this->hasMany(SpecificationItem::class, 'parent_id');
    }
}
