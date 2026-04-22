<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['title','type','link_type','link_value','sort_order'];
    public function sections() { return $this->hasMany(MenuSection::class)->orderBy('sort_order'); }
    public function items() { return $this->hasMany(MenuItem::class)->whereNull('menu_section_id')->orderBy('sort_order'); }
}
