<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'image',
        'sort_order',
    ];

    protected $appends = [
        'image_url',
    ];

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order', 'asc');
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'kategori', 'name');
    }

    /**
     * Get accessible image URL
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/Ramen_kategori.png');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }

        return asset('storage/' . $this->image);
    }
}
