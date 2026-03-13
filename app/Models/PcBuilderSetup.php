<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcBuilderSetup extends Model
{
    protected $fillable = [
        'user_id', 'build_data'
    ];
    
    protected $casts = [
        'build_data' => 'array'
    ];

    
}
