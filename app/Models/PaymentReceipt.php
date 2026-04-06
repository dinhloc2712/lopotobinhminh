<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentReceipt extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'note',
        'payment_date',
        'created_by',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }


    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
