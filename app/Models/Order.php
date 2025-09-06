<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cart_id',
        'total_price',
        'shipping_method',
        'shipping_cost',
        'midtrans_order_id',
        'payment_status',
        'order_status',
        'payment_type',
        'payment_token',
        'payment_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function ratings(): HasManyThrough
    {
        return $this->hasManyThrough(
            Rating::class,
            OrderItem::class,
            'order_id',      // Foreign key di OrderItem
            'product_id',    // Foreign key di Rating
            'id',            // Local key di Order
            'product_id'     // Local key di OrderItem
        );
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
