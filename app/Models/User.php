<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'currency',
        'balance_inr',
        'balance_usd',
        'shadow_balance_inr',
        'shadow_balance_usd',
        'stripe_customer_id',
        'referral_code',
        'referred_by',
        'referral_earnings_inr',
        'referral_earnings_usd',
        'last_referral_at',
        'ai_enabled',
        'ai_blog_generation_enabled',
        'ai_access_enabled',
        'ai_content_optimization_enabled',
        'ai_access_notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'balance_inr' => 'decimal:4',
        'balance_usd' => 'decimal:4',
        'shadow_balance_inr' => 'decimal:4',
        'shadow_balance_usd' => 'decimal:4',
        'referral_earnings_inr' => 'decimal:4',
        'referral_earnings_usd' => 'decimal:4',
        'ai_enabled' => 'boolean',
        'ai_blog_generation_enabled' => 'boolean',
        'ai_access_enabled' => 'boolean',
        'ai_content_optimization_enabled' => 'boolean',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance_inr' => 'decimal:4',
            'balance_usd' => 'decimal:4',
            'shadow_balance_inr' => 'decimal:4',
            'shadow_balance_usd' => 'decimal:4',
            'referral_earnings_inr' => 'decimal:4',
            'referral_earnings_usd' => 'decimal:4',
            'last_referral_at' => 'datetime',
            'ai_enabled' => 'boolean',
            'ai_blog_generation_enabled' => 'boolean',
            'ai_access_enabled' => 'boolean',
            'ai_content_optimization_enabled' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user has AI access enabled
     */
    public function hasAiAccess(): bool
    {
        return $this->ai_access_enabled ?? false;
    }

    /**
     * Check if user can use AI blog generation
     */
    public function canUseAiBlogGeneration(): bool
    {
        return $this->ai_blog_generation_enabled ?? false;
    }

    /**
     * Check if user can use AI content optimization
     */
    public function canUseAiContentOptimization(): bool
    {
        return $this->ai_content_optimization_enabled ?? false;
    }

    /**
     * Check if user can use any AI features
     */
    public function canUseAi(): bool
    {
        return $this->hasAiAccess() && ($this->canUseAiBlogGeneration() || $this->canUseAiContentOptimization());
    }

    /**
     * Get the links for the user
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    /**
     * Get the earnings for the user
     */
    public function earnings(): HasMany
    {
        return $this->hasMany(UserEarning::class);
    }

    /**
     * Get the withdrawals for the user
     */
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    /**
     * Get the user's blog posts
     */
    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    /**
     * Get total earnings for the user
     */
    public function getTotalEarningsAttribute(): float
    {
        return $this->earnings()->where('status', 'approved')->sum('amount');
    }

    /**
     * Get pending earnings for the user
     */
    public function getPendingEarningsAttribute(): float
    {
        return $this->earnings()->where('status', 'pending')->sum('amount');
    }

    /**
     * Get total clicks across all user's links
     */
    public function getTotalClicksAttribute(): int
    {
        return $this->links()->withCount('clicks')->get()->sum('clicks_count');
    }

    /**
     * Get unique clicks across all user's links
     */
    public function getUniqueClicksAttribute(): int
    {
        return $this->links()->withCount([
            'clicks' => function ($query) {
                $query->where('is_unique', true);
            }
        ])->get()->sum('clicks_count');
    }

    /**
     * Get today's earnings
     */
    public function getTodayEarningsAttribute(): float
    {
        return $this->earnings()
            ->where('status', 'approved')
            ->whereDate('created_at', today())
            ->sum('amount');
    }

    /**
     * Get this month's earnings
     */
    public function getThisMonthEarningsAttribute(): float
    {
        return $this->earnings()
            ->where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    /**
     * Get the user's preferred currency
     */
    public function getPreferredCurrencyAttribute(): string
    {
        return $this->currency ?? 'INR';
    }

    /**
     * Get the user's balance in their preferred currency
     */
    public function getBalanceAttribute(): float
    {
        return $this->getBalanceInCurrency($this->preferred_currency);
    }

    /**
     * Get balance in specific currency
     */
    public function getBalanceInCurrency(string $currency): float
    {
        switch (strtoupper($currency)) {
            case 'INR':
                return $this->balance_inr ?? 0;
            case 'USD':
                return $this->balance_usd ?? 0;
            default:
                return $this->balance_inr ?? 0;
        }
    }

    /**
     * Set balance in specific currency
     */
    public function setBalanceInCurrency(string $currency, float $amount): void
    {
        switch (strtoupper($currency)) {
            case 'INR':
                $this->balance_inr = $amount;
                break;
            case 'USD':
                $this->balance_usd = $amount;
                break;
        }
    }

    /**
     * Increment balance in specific currency
     */
    public function incrementBalanceInCurrency(string $currency, float $amount): void
    {
        $currentBalance = $this->getBalanceInCurrency($currency);
        $this->setBalanceInCurrency($currency, (string) ($currentBalance + $amount));
    }

    /**
     * Decrement balance in specific currency
     */
    public function decrementBalanceInCurrency(string $currency, float $amount): void
    {
        $currentBalance = $this->getBalanceInCurrency($currency);
        $this->setBalanceInCurrency($currency, max(0.0, $currentBalance - $amount));
    }

    /**
     * Get shadow balance in specific currency
     */
    public function getShadowBalanceInCurrency(string $currency): float
    {
        switch (strtoupper($currency)) {
            case 'INR':
                return $this->shadow_balance_inr ?? 0;
            case 'USD':
                return $this->shadow_balance_usd ?? 0;
            default:
                return $this->shadow_balance_inr ?? 0;
        }
    }

    /**
     * Set shadow balance in specific currency
     */
    public function setShadowBalanceInCurrency(string $currency, float $amount): void
    {
        switch (strtoupper($currency)) {
            case 'INR':
                $this->shadow_balance_inr = $amount;
                break;
            case 'USD':
                $this->shadow_balance_usd = $amount;
                break;
        }
    }

    /**
     * Increment shadow balance in specific currency
     */
    public function incrementShadowBalanceInCurrency(string $currency, float $amount): void
    {
        $currentBalance = $this->getShadowBalanceInCurrency($currency);
        $this->setShadowBalanceInCurrency($currency, $currentBalance + $amount);
    }

    /**
     * Get total earnings in specific currency
     */
    public function getTotalEarningsInCurrency(string $currency): float
    {
        return $this->earnings()
            ->where('status', 'approved')
            ->where('currency', $currency)
            ->sum('amount');
    }

    /**
     * Get pending earnings in specific currency
     */
    public function getPendingEarningsInCurrency(string $currency): float
    {
        return $this->earnings()
            ->where('status', 'pending')
            ->where('currency', $currency)
            ->sum('amount');
    }

    /**
     * Get today's earnings in specific currency
     */
    public function getTodayEarningsInCurrency(string $currency): float
    {
        return $this->earnings()
            ->where('status', 'approved')
            ->where('currency', $currency)
            ->whereDate('created_at', today())
            ->sum('amount');
    }

    /**
     * Get this month's earnings in specific currency
     */
    public function getThisMonthEarningsInCurrency(string $currency): float
    {
        return $this->earnings()
            ->where('status', 'approved')
            ->where('currency', $currency)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    /**
     * Check if user has premium subscription
     */
    public function isPremium(): bool
    {
        // Check if user has an active subscription
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->exists();
    }

    /**
     * Get active subscription
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->first();
    }

    /**
     * Get all subscriptions
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get subscription history
     */
    public function subscriptionHistory()
    {
        return $this->subscriptions()->orderBy('created_at', 'desc');
    }

    /**
     * Get referrals made by this user
     */
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /**
     * Get referral that brought this user
     */
    public function referral()
    {
        return $this->belongsTo(Referral::class, 'referred_by');
    }

    /**
     * Check if user is on trial
     */
    public function onTrial(): bool
    {
        $subscription = $this->activeSubscription();
        return $subscription && $subscription->onTrial();
    }

    /**
     * Get trial days remaining
     */
    public function trialDaysRemaining(): int
    {
        $subscription = $this->activeSubscription();
        return $subscription ? $subscription->trialDaysRemaining() : 0;
    }

    /**
     * Get subscription status
     */
    public function getSubscriptionStatus(): string
    {
        if ($this->isPremium()) {
            return 'premium';
        }

        if ($this->onTrial()) {
            return 'trial';
        }

        return 'free';
    }

    /**
     * Get subscription status display name
     */
    public function getSubscriptionStatusDisplayName(): string
    {
        return match ($this->getSubscriptionStatus()) {
            'premium' => 'Premium',
            'trial' => 'Trial',
            'free' => 'Free',
            default => 'Unknown',
        };
    }

    /**
     * Get subscription status badge class
     */
    public function getSubscriptionStatusBadgeClass(): string
    {
        return match ($this->getSubscriptionStatus()) {
            'premium' => 'bg-purple-100 text-purple-800',
            'trial' => 'bg-blue-100 text-blue-800',
            'free' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Check if user can upgrade their subscription
     */
    public function canUpgrade(): bool
    {
        $activeSubscription = $this->activeSubscription();
        if (!$activeSubscription) {
            return true; // No subscription, can subscribe
        }

        // Check if there's a higher tier plan available
        $currentPlan = $activeSubscription->plan;
        $higherPlans = \App\Models\SubscriptionPlan::where('is_active', true)
            ->where('billing_cycle', $currentPlan->billing_cycle)
            ->where('sort_order', '>', $currentPlan->sort_order)
            ->exists();

        return $higherPlans;
    }

    /**
     * Check if user can downgrade their subscription
     */
    public function canDowngrade(): bool
    {
        $activeSubscription = $this->activeSubscription();
        if (!$activeSubscription) {
            return false; // No subscription to downgrade
        }

        // Check if there's a lower tier plan available
        $currentPlan = $activeSubscription->plan;
        if (!$currentPlan) {
            return false;
        }

        $lowerPlans = \App\Models\SubscriptionPlan::where('is_active', true)
            ->where('billing_cycle', $currentPlan->billing_cycle)
            ->where('sort_order', '<', $currentPlan->sort_order)
            ->exists();

        return $lowerPlans;
    }

    /**
     * Get available upgrade plans
     */
    public function getAvailableUpgradePlans()
    {
        $activeSubscription = $this->activeSubscription();
        if (!$activeSubscription) {
            return collect();
        }

        $currentPlan = $activeSubscription->plan;
        return \App\Models\SubscriptionPlan::where('is_active', true)
            ->where('billing_cycle', $currentPlan->billing_cycle)
            ->where('sort_order', '>', $currentPlan->sort_order)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get available downgrade plans
     */
    public function getAvailableDowngradePlans()
    {
        $activeSubscription = $this->activeSubscription();
        if (!$activeSubscription) {
            return collect();
        }

        $activeSubscription = $this->activeSubscription();
        if (!$activeSubscription) {
            return collect();
        }

        $currentPlan = $activeSubscription->plan;
        if (!$currentPlan) {
            return collect();
        }

        return \App\Models\SubscriptionPlan::where('is_active', true)
            ->where('billing_cycle', $currentPlan->billing_cycle)
            ->where('sort_order', '<', $currentPlan->sort_order)
            ->orderBy('sort_order', 'desc')
            ->get();
    }

    /**
     * Check if user has referral code
     */
    public function hasReferralCode(): bool
    {
        return !empty($this->referral_code);
    }

    /**
     * Generate referral code
     */
    public function generateReferralCode(): string
    {
        if ($this->hasReferralCode()) {
            return $this->referral_code;
        }

        $code = strtoupper(substr(md5($this->id . time()), 0, 8));
        $this->update(['referral_code' => $code]);

        return $code;
    }

    /**
     * Get referral earnings
     */
    public function getReferralEarnings(?string $currency = null): float
    {
        $currency = $currency ?? $this->preferred_currency;
        $column = 'amount_' . strtolower($currency);

        return $this->referrals()
            ->where('status', 'completed')
            ->sum($column);
    }

    /**
     * Get referral count
     */
    public function getReferralCount(): int
    {
        return $this->referrals()->where('status', 'completed')->count();
    }

    /**
     * Generate a new API key for the user
     */
    public function generateApiKey(): string
    {
        $apiKey = bin2hex(random_bytes(32)); // 64 character hex string

        $this->update([
            'api_key' => $apiKey,
            'api_key_generated_at' => now(),
        ]);

        return $apiKey;
    }

    /**
     * Revoke the user's API key
     */
    public function revokeApiKey(): void
    {
        $this->update([
            'api_key' => null,
            'api_key_generated_at' => null,
        ]);
    }

    /**
     * Check if user has a valid API key
     */
    public function hasValidApiKey(): bool
    {
        return !empty($this->api_key) && $this->api_key_generated_at;
    }

    /**
     * Update last API access timestamp
     */
    public function updateLastApiAccess(): void
    {
        $this->update(['last_api_access_at' => now()]);
    }
}
