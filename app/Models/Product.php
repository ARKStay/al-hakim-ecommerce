<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'sizes_id',
        'color',
        'price',
        'stock',
        'description',
        'image',
        'average_rating',
        'total_ratings',
    ];


    protected $with = ['category', 'sizes'];

    public function scopeFilter($query, array $filters)
    {
        // Pastikan pencarian berdasarkan nama produk
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes(): BelongsTo
    {
        return $this->belongsTo(Sizes::class, 'sizes_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function updateRatings()
    {
        // Ambil semua rating produk ini
        $ratings = $this->ratings();

        // Hitung total dan rata-rata rating
        $totalRatings = $ratings->count();
        $averageRating = $ratings->avg('rating') ?? 0; // Pastikan tidak null

        // Update langsung ke model
        $this->update([
            'average_rating' => round($averageRating, 2),
            'total_ratings' => $totalRatings,
        ]);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function cartItems()
    {
        return $this->hasMany(Cart_Item::class);
    }
}
