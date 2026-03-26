<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSection extends Model
{
    protected $fillable = ['menu_id','title','sort_order','link_type','link_value'];
    public function items() { return $this->hasMany(MenuItem::class)->orderBy('sort_order'); }
}
