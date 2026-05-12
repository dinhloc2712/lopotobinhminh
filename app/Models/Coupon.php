<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type', // fixed, percent
        'discount_amount',
        'discount_percentage',
        'max_discount_amount',
        'min_order_value',
        // 'product_id', // Removed
        'quantity',
        'used',
        'user_usage_limit',
        'start_date',
        'expiry_date',
        'status',
        'priority',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'expiry_date' => 'datetime',
        'discount_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_order_value' => 'decimal:2',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_product');
    }

    public function isValid()
    {
        if ($this->status !== 'active') return false;
        
        $now = now();
        if ($this->start_date && $this->start_date > $now) return false;
        if ($this->expiry_date && $this->expiry_date < $now) return false;

        if ($this->quantity > 0 && $this->used >= $this->quantity) return false;

        return true;
    }

    /**
     * Check if cart satisfies coupon conditions
     * @param array $cart Session Cart Array
     * @return array ['valid' => bool, 'reason' => string, 'applicable_items' => array]
     */
    public function validateCart($cart)
    {
        if (!$this->isValid()) {
            return ['valid' => false, 'reason' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'];
        }

        // 1. Calculate Cart Subtotal
        $cartSubtotal = 0;
        foreach($cart as $item) {
            $cartSubtotal += $item['price'] * $item['quantity'];
        }

        if ($cartSubtotal < $this->min_order_value) {
            return ['valid' => false, 'reason' => 'Đơn hàng chưa đạt giá trị tối thiểu: ' . number_format($this->min_order_value) . 'đ'];
        }

        // 2. Check Product Conditions
        $couponProductIds = $this->products()->pluck('product_id')->toArray();
        
        // If no specific products are set, it's a Global Coupon
        if (empty($couponProductIds)) {
            return ['valid' => true, 'reason' => '', 'applicable_items' => $cart];
        }

        // Identify matching items in cart
        $applicableItems = [];
        foreach ($cart as $id => $item) {
            if (in_array($id, $couponProductIds)) {
                $applicableItems[$id] = $item;
            }
        }

        // At least one matching product required
        if (empty($applicableItems)) {
            return ['valid' => false, 'reason' => 'Mã này chỉ áp dụng cho một số sản phẩm nhất định.'];
        }

        return ['valid' => true, 'reason' => '', 'applicable_items' => $applicableItems];
    }

    /**
     * Calculate discount amount based on validated items
     * @param float $subtotal Total value of APPLICABLE items (not necessarily whole cart)
     * @return float
     */
    public function calculateDiscount($subtotal)
    {
        $discount = 0;

        if ($this->type === 'fixed') {
            $discount = $this->discount_amount;
        } elseif ($this->type === 'percent') {
            $discount = ($subtotal * $this->discount_percentage) / 100;
            if ($this->max_discount_amount > 0) {
                $discount = min($discount, $this->max_discount_amount);
            }
        }
        
        // Discount cannot exceed subtotal
        return min($discount, $subtotal);
    }
}
