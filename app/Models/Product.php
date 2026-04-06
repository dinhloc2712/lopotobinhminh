<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 
        'price', 'sale_price', 'stock', 'sold', 
        'thumbnail', 'images', 'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'sold' => 'integer',
        'images' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (!$product->slug) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . '₫';
    }

    /**
     * Get formatted sale price.
     */
    public function getFormattedSalePriceAttribute()
    {
        return number_format($this->sale_price, 0, ',', '.') . '₫';
    }

    /**
     * Check if product is on sale.
     */
    public function getIsOnSaleAttribute()
    {
        return $this->sale_price > 0 && $this->sale_price < $this->price;
    }

    /**
     * Calculate discount percentage.
     */
    public function getDiscountPercentAttribute()
    {
        if ($this->price > 0 && $this->sale_price > 0 && $this->sale_price < $this->price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }
}
