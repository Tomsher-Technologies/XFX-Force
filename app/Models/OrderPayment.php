<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_status',
        'payment_details',
    ];

    protected $casts = [
        'payment_details' => 'array',
    ];
}
