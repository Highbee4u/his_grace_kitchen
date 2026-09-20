<?php

namespace App\Models;

use App\Support\Money;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Combo extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'items' => 'json',
            'customisation_slots' => 'json',
            'is_active' => 'boolean',
        ];
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

        return $this->image_url ?? 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80';
    }
}
