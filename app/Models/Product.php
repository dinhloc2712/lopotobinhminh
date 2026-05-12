<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'description', 
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

    /**
     * Get approved reviews for product.
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Get average rating score.
     */
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?: 0, 1);
    }

    /**
     * Get total number of reviews.
     */
    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Get distribution of stars (1-5).
     */
    public function getReviewDistribution()
    {
        $counts = $this->reviews()
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->all();

        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = $counts[$i] ?? 0;
        }
        return $distribution;
    }
}
