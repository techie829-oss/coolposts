<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_inr',
        'price_usd',
        'billing_cycle',
        'duration_days',
        'features',
        'is_active',
        'is_popular',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'price_inr' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'duration_days' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get all active subscriptions for this plan
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get active subscriptions for this plan
     */
    public function activeSubscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class)->where('status', 'active');
    }

    /**
     * Get price for specific currency
     */
    public function getPrice(string $currency): float
    {
        return $currency === 'INR' ? $this->price_inr : $this->price_usd;
    }

    /**
     * Get formatted price for specific currency
     */
    public function getFormattedPrice(string $currency): string
    {
        $price = $this->getPrice($currency);
        $symbol = $currency === 'INR' ? '₹' : '$';
        return $symbol . number_format($price, 2);
    }

    /**
     * Get billing cycle display name
     */
    public function getBillingCycleDisplayName(): string
    {
        return ucfirst($this->billing_cycle);
    }

    /**
     * Get duration display name
     */
    public function getDurationDisplayName(): string
    {
        if ($this->duration_days === 30) {
            return 'Monthly';
        } elseif ($this->duration_days === 365) {
            return 'Yearly';
        }
        return $this->duration_days . ' days';
    }

    /**
     * Check if plan is yearly
     */
    public function isYearly(): bool
    {
        return $this->billing_cycle === 'yearly';
    }

    /**
     * Check if plan is monthly
     */
    public function isMonthly(): bool
    {
        return $this->billing_cycle === 'monthly';
    }

    /**
     * Get savings percentage for yearly plans
     */
    public function getYearlySavings(): ?float
    {
        if (!$this->isYearly()) {
            return null;
        }

        // Find corresponding monthly plan
        $monthlyPlan = static::where('slug', $this->slug)
            ->where('billing_cycle', 'monthly')
            ->first();

        if (!$monthlyPlan) {
            return null;
        }

        $monthlyPrice = $monthlyPlan->getPrice($this->currency ?? 'INR');
        $yearlyPrice = $this->getPrice($this->currency ?? 'INR');
        $monthlyEquivalent = $monthlyPrice * 12;

        if ($monthlyEquivalent <= 0) {
            return null;
        }

        return round((($monthlyEquivalent - $yearlyPrice) / $monthlyEquivalent) * 100, 1);
    }

    /**
     * Get features as HTML list
     */
    public function getFeaturesHtml(): string
    {
        if (empty($this->features)) {
            return '';
        }

        $html = '<ul class="space-y-2">';
        foreach ($this->features as $feature) {
            $html .= '<li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>' . e($feature) . '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Scope for active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for popular plans
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    /**
     * Scope for plans by billing cycle
     */
    public function scopeByBillingCycle($query, string $cycle)
    {
        return $query->where('billing_cycle', $cycle);
    }

    /**
     * Get all active plans ordered by sort order
     */
    public static function getActivePlans()
    {
        return static::active()->orderBy('sort_order')->get();
    }

    /**
     * Get plans by billing cycle
     */
    public static function getPlansByCycle(string $cycle)
    {
        return static::active()->byBillingCycle($cycle)->orderBy('sort_order')->get();
    }
}
