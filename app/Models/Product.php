<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'brand_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'image',
        'description',
        'stock',
        'price',
        'is_available',
        'is_featured',
        'published_at',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function userFavorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favorites');
    }

    public function userCarts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, CartItem::class)->withPivot(['quantity','total_price'])->withTimestamps();
    }

    protected $casts = [
        'price' => 'float',
    ];

}
