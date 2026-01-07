<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BrandingSetting extends Model
{
    protected $fillable = [
        'brand_name',
        'brand_logo',
        'primary_color',
        'secondary_color',
        'accent_color',
        'gradient_start',
        'gradient_end',
        'gradient_third',
        'brand_tagline',
        'brand_description',
        'favicon',
    ];

    /**
     * Get branding settings (cached for performance)
     */
    public static function getSettings(): self
    {
        return Cache::remember('branding_settings', 3600, function () {
            return self::first() ?? self::createDefaultSettings();
        });
    }

    /**
     * Create default branding settings
     */
    public static function createDefaultSettings(): self
    {
        return self::create([
            'brand_name' => 'CoolPosts',
            'brand_logo' => null,
            'primary_color' => '#8b5cf6',
            'secondary_color' => '#ec4899',
            'accent_color' => '#ef4444',
            'gradient_start' => '#8b5cf6',
            'gradient_end' => '#ec4899',
            'gradient_third' => '#ef4444',
            'brand_tagline' => 'Link Monetization',
            'brand_description' => 'Transform your links into revenue streams.',
            'favicon' => null,
        ]);
    }
}
