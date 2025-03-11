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
        'image',
        'payment_status',
        'order_status',
    ];

    /**
     * Relasi dengan model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // /**
    //  * Relasi dengan model Cart
    //  */
    // public function cart()
    // {
    //     return $this->belongsTo(Cart::class);
    // }

    // public function products()
    // {
    //     return $this->hasManyThrough(Product::class, Cart_Item::class, 'cart_id', 'id', 'cart_id', 'product_id');
    // }

    // /**
    //  * Relasi dengan model CartItem untuk mendapatkan barang di dalam order
    //  */
    // public function cartItems()
    // {
    //     return $this->hasMany(Cart_Item::class, Cart::class, 'id', 'cart_id');
    // }

    // public function ratings()
    // {
    //     return $this->hasManyThrough(Rating::class, Cart_Item::class, 'cart_id', 'product_id', 'cart_id', 'product_id');
    // }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function ratings(): HasManyThrough
    {
        return $this->hasManyThrough(
            Rating::class,
            OrderItem::class,
            'order_id', // Foreign key di `OrderItem`
            'product_id', // Foreign key di `Rating`
            'id', // Primary key di `Order`
            'product_id' // Primary key di `OrderItem`
        );
    }
}
