<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'is_test_mode',
        'environment',
        'config',
        'supported_currencies',
        'supported_countries',
        'transaction_fee_percentage',
        'transaction_fee_fixed',
        'webhook_url',
        'webhook_secret',
        'last_webhook_received_at',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean',
        'config' => 'array',
        'supported_currencies' => 'array',
        'supported_countries' => 'array',
        'transaction_fee_percentage' => 'decimal:2',
        'transaction_fee_fixed' => 'decimal:2',
        'last_webhook_received_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    /**
     * Get all transactions for this gateway
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    /**
     * Get configuration value
     */
    public function getConfig(string $key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }

    /**
     * Set configuration value
     */
    public function setConfig(string $key, $value): void
    {
        $config = $this->config ?? [];
        $config[$key] = $value;
        $this->update(['config' => $config]);
    }

    /**
     * Check if gateway supports currency
     */
    public function supportsCurrency(string $currency): bool
    {
        return in_array(strtoupper($currency), $this->supported_currencies ?? []);
    }

    /**
     * Check if gateway supports country
     */
    public function supportsCountry(string $country): bool
    {
        if (empty($this->supported_countries)) {
            return true; // No restrictions
        }
        return in_array(strtoupper($country), $this->supported_countries);
    }

    /**
     * Get transaction fee for amount
     */
    public function getTransactionFee(float $amount): float
    {
        $percentageFee = ($amount * $this->transaction_fee_percentage) / 100;
        $fixedFee = $this->transaction_fee_fixed;
        return $percentageFee + $fixedFee;
    }

    /**
     * Get total amount with fees
     */
    public function getTotalWithFees(float $amount): float
    {
        return $amount + $this->getTransactionFee($amount);
    }

    /**
     * Get formatted fixed fee
     */
    public function getFormattedFixedFee(): string
    {
        $currency = $this->supported_currencies[0] ?? 'USD';
        $symbol = $currency === 'INR' ? '₹' : '$';
        return $symbol . number_format($this->transaction_fee_fixed, 2);
    }

    /**
     * Check if gateway is in test mode
     */
    public function isTestMode(): bool
    {
        return $this->is_test_mode;
    }

    /**
     * Check if gateway is in live mode
     */
    public function isLiveMode(): bool
    {
        return !$this->is_test_mode;
    }

    /**
     * Get environment display name
     */
    public function getEnvironmentDisplayName(): string
    {
        return ucfirst($this->environment);
    }

    /**
     * Get environment badge class
     */
    public function getEnvironmentBadgeClass(): string
    {
        return match($this->environment) {
            'live' => 'bg-green-100 text-green-800',
            'test' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return $this->is_active
            ? 'bg-green-100 text-green-800'
            : 'bg-red-100 text-red-800';
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayName(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Scope for active gateways
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for test mode gateways
     */
    public function scopeTestMode($query)
    {
        return $query->where('is_test_mode', true);
    }

    /**
     * Scope for live mode gateways
     */
    public function scopeLiveMode($query)
    {
        return $query->where('is_test_mode', false);
    }

    /**
     * Get active gateways ordered by sort order
     */
    public static function getActiveGateways()
    {
        return static::active()->orderBy('sort_order')->get();
    }

    /**
     * Get gateway by slug
     */
    public static function getBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Get gateway configuration for specific currency
     */
    public static function getForCurrency(string $currency): ?self
    {
        return static::active()
            ->whereJsonContains('supported_currencies', strtoupper($currency))
            ->orderBy('sort_order')
            ->first();
    }
}
