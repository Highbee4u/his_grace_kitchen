<?php

namespace App\Models;

use App\Support\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MenuItem extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'allergens' => 'json',
            'tags' => 'json',
            'is_vegetarian' => 'boolean',
            'is_available' => 'boolean',
            'daily_limit' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(MenuItemVariant::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return Money::format($this->price_minor, $this->currency ?? 'NGN');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute(): float
    {
        $avg = $this->reviews()->where('status', 'approved')->avg('rating');

        return $avg ? round((float) $avg, 1) : 5.0;
    }

    public function getApprovedReviewsCountAttribute(): int
    {
        return $this->reviews()->where('status', 'approved')->count();
    }

    public function getImageAttribute(): string
    {
        if ($this->hasMedia('images')) {
            return $this->getFirstMediaUrl('images');
        }

        return $this->image_url ?? 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80';
    }
}
