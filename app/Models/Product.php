<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'average_rating',
        'total_ratings',
        'sold',
    ];

    /**
     * Filtering pencarian produk
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhereHas('variants', function ($q) use ($search) {
                    $q->where('color', 'like', '%' . $search . '%')
                        ->orWhere('size', 'like', '%' . $search . '%')
                        ->orWhere('price', 'like', '%' . $search . '%')
                        ->orWhere('stock', 'like', '%' . $search . '%')
                        ->orWhere('weight', 'like', '%' . $search . '%')
                        ->orWhere('sold', 'like', '%' . $search . '%');
                })
                ->orWhereHas('ratings', function ($q) use ($search) {
                    $q->where('rating', 'like', '%' . $search . '%');
                })
                ->orWhere('sold', 'like', '%' . $search . '%')  // Kalau field `sold` ada di tabel `products`
                ->orWhere('average_rating', 'like', '%' . $search . '%')
                ->orWhere('total_ratings', 'like', '%' . $search . '%');
        });
    }

    /**
     * Relasi ke rating produk
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Relasi ke varian produk (warna + ukuran)
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Relasi ke item keranjang
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Mengupdate rating dan jumlah rating
     */
    public function updateRatings()
    {
        $ratings = $this->ratings();
        $totalRatings = $ratings->count();
        $averageRating = $ratings->avg('rating') ?? 0;

        $this->update([
            'average_rating' => round($averageRating, 2),
            'total_ratings' => $totalRatings,
        ]);
    }

    /**
     * Route pakai slug
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Konfigurasi sluggable
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
