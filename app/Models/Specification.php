<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    protected $fillable = [
        'main_title',
        'display_title',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the specification items associated with the specification.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(SpecificationItem::class, 'main_specification_id');
    }
}
