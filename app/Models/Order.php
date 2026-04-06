<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'customer_id',
        'referrer_id',
        'order_code',
        'status',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PaymentReceipt::class)->latest();
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }
}
