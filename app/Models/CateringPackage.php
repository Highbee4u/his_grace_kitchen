<?php

namespace App\Models;

use App\Support\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CateringPackage extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['includes' => 'json', 'is_active' => 'boolean'];
    }

    public function requests(): HasMany
    {
        return $this->hasMany(CateringRequest::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return Money::format($this->price_minor, $this->currency ?? 'NGN');
    }

    public function getImageAttribute(): string
    {
        if ($this->hasMedia('images')) {
            return $this->getFirstMediaUrl('images');
        }

        return $this->image_url ?? 'https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&w=800&q=80';
    }
}
