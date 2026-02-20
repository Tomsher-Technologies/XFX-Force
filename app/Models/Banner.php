<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\Upload;
use Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'mobile_image',
        'title',
        'sub_title',
        'btn_text',
        'link_type',
        'link_ref',
        'link_ref_id',
        'link',
        'status',
    ];

    

    public function getALink()
    {
        if ($this->link_ref == 'product' && $this->link_ref_id !== null) {
            $product = Product::where('id', $this->link_ref_id)->select('slug')->first()->slug;
            return route('roducts.all', [
                'slug' => $product
            ]);
        } elseif ($this->link_ref == 'category' && $this->link_ref_id !== null) {
            $product = Category::where('id', $this->link_ref_id)->select('slug')->first()->slug;
            return route('roducts.all', [
                'category_slug' => $product
            ]);
        } elseif ($this->link_ref == 'external' && $this->link !== null) {
            return $this->link;
        } else {
            return '#';
        }
    }

    public function getBannerLink()
    {
        if ($this->link_ref == 'product' && $this->link_ref_id !== null) {
            $product = Product::where('id', $this->link_ref_id)->select('slug')->first();
            if($product){
                return $product->slug;
            }else{
                return '#';
            }
        } elseif ($this->link_ref == 'category' && $this->link_ref_id !== null) {
            $product = Category::where('id', $this->link_ref_id)->first();
            if($product){
                return $product->getTranslation('slug',getActiveLanguage());
            }else{
                return '#';
            }
        } elseif ($this->link_ref == 'external' && $this->link !== null) {
            return $this->link;
        } else {
            return '#';
        }
    }

    public function getBannerSKU()
    {
        if ($this->link_ref == 'product' && $this->link_ref_id !== null) {
            $product = Product::where('id', $this->link_ref_id)->select('sku')->first();
            if($product){
                return $product->sku;
            }else{
                return '';
            }
        } 
        return '';
    }

    public static function boot()
    {
        static::creating(function ($model) {
            Cache::forget('smallBanners');
            Cache::forget('ads_banners');
        });

        static::updating(function ($model) {
            Cache::forget('smallBanners');
            Cache::forget('ads_banners');
        });

        static::deleting(function ($model) {
            Cache::forget('smallBanners');
            Cache::forget('ads_banners');
        });

        parent::boot();
    }
}
