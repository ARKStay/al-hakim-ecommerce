<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Scope untuk filtering berdasarkan pencarian
    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['search'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%');
            })->orWhereHas('product', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%');
            })->orWhere('comment', 'like', '%' . $filters['search'] . '%');
        }
    }
}
