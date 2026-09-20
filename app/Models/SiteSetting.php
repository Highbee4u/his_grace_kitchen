<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['value' => 'json'];
    }

    protected static function booted(): void
    {
        static::saved(function ($setting) {
            Cache::forget("site_setting:{$setting->key}");
            Cache::forget('site_setting:site_name');
            Cache::forget('site_setting:business_name');

            // Synchronize site_name and business_name if either is updated
            if ($setting->key === 'site_name' && ! empty($setting->value)) {
                static::withoutEvents(function () use ($setting) {
                    static::updateOrCreate(
                        ['key' => 'business_name'],
                        ['value' => $setting->value, 'type' => 'string']
                    );
                });
                Cache::forget('site_setting:business_name');
            } elseif ($setting->key === 'business_name' && ! empty($setting->value)) {
                static::withoutEvents(function () use ($setting) {
                    static::updateOrCreate(
                        ['key' => 'site_name'],
                        ['value' => $setting->value, 'type' => 'string']
                    );
                });
                Cache::forget('site_setting:site_name');
            }
        });

        static::deleted(function ($setting) {
            Cache::forget("site_setting:{$setting->key}");
            Cache::forget('site_setting:site_name');
            Cache::forget('site_setting:business_name');
        });
    }

    /**
     * Retrieve a setting value by key with caching.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("site_setting:{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Retrieve current site/brand name.
     */
    public static function getSiteName(): string
    {
        return static::get('site_name')
            ?? static::get('business_name')
            ?? config('app.name', 'His Grace Kitchen LTD');
    }
}
