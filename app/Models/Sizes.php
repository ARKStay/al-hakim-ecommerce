<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sizes extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = true;

    public function scopeFilter($query, array $filters)
    {
        // Pastikan pencarian berdasarkan nama produk
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function products() : HasMany
    {
        return $this->hasMany(Product::class);
    }
}
