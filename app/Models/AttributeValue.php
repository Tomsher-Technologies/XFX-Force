<?php

namespace App\Models;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $fillable = [
        'attribute_id',
        'value',
        'is_active',

    ];

    /**
     * Get the attribute that this value belongs to.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
