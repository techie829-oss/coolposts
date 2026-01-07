<?php

namespace App\Services;

class CurrencyService
{
    /**
     * Supported currencies
     */
    const SUPPORTED_CURRENCIES = ['INR', 'USD'];

    /**
     * Default currency
     */
    const DEFAULT_CURRENCY = 'INR';

    /**
     * Exchange rates (INR to USD) - This should be updated regularly via API
     * Current approximate rate: 1 USD = 83 INR
     */
    const EXCHANGE_RATES = [
        'INR' => [
            'USD' => 0.012, // 1 INR = 0.012 USD
        ],
        'USD' => [
            'INR' => 83.0, // 1 USD = 83 INR
        ]
    ];

    /**
     * Currency symbols
     */
    const CURRENCY_SYMBOLS = [
        'INR' => '₹',
        'USD' => '$',
    ];

    /**
     * Currency names
     */
    const CURRENCY_NAMES = [
        'INR' => 'Indian Rupee',
        'USD' => 'US Dollar',
    ];

    /**
     * Convert amount from one currency to another
     *
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public function convert(float $amount, string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        if (!isset(self::EXCHANGE_RATES[$fromCurrency][$toCurrency])) {
            throw new \InvalidArgumentException("Conversion from {$fromCurrency} to {$toCurrency} not supported");
        }

        $rate = self::EXCHANGE_RATES[$fromCurrency][$toCurrency];
        return round($amount * $rate, 4);
    }

    /**
     * Convert amount to default currency (INR)
     *
     * @param float $amount
     * @param string $fromCurrency
     * @return float
     */
    public function convertToDefault(float $amount, string $fromCurrency): float
    {
        return $this->convert($amount, $fromCurrency, self::DEFAULT_CURRENCY);
    }

    /**
     * Convert amount from default currency (INR) to target currency
     *
     * @param float $amount
     * @param string $toCurrency
     * @return float
     */
    public function convertFromDefault(float $amount, string $toCurrency): float
    {
        return $this->convert($amount, self::DEFAULT_CURRENCY, $toCurrency);
    }

    /**
     * Format amount with currency symbol
     *
     * @param float $amount
     * @param string $currency
     * @param bool $showSymbol
     * @return string
     */
    public function format(float $amount, string $currency, bool $showSymbol = true): string
    {
        $symbol = $showSymbol ? (self::CURRENCY_SYMBOLS[$currency] ?? $currency) : '';

        switch ($currency) {
            case 'INR':
                return $symbol . number_format($amount, 2);
            case 'USD':
                return $symbol . number_format($amount, 4);
            default:
                return $symbol . number_format($amount, 2);
        }
    }

    /**
     * Get currency symbol
     *
     * @param string $currency
     * @return string
     */
    public function getSymbol(string $currency): string
    {
        return self::CURRENCY_SYMBOLS[$currency] ?? $currency;
    }

    /**
     * Get currency name
     *
     * @param string $currency
     * @return string
     */
    public function getName(string $currency): string
    {
        return self::CURRENCY_NAMES[$currency] ?? $currency;
    }

    /**
     * Check if currency is supported
     *
     * @param string $currency
     * @return bool
     */
    public function isSupported(string $currency): bool
    {
        return in_array(strtoupper($currency), self::SUPPORTED_CURRENCIES);
    }

    /**
     * Get supported currencies
     *
     * @return array
     */
    public function getSupportedCurrencies(): array
    {
        return self::SUPPORTED_CURRENCIES;
    }

    /**
     * Get default currency
     *
     * @return string
     */
    public function getDefaultCurrency(): string
    {
        return self::DEFAULT_CURRENCY;
    }

    /**
     * Get exchange rate between two currencies
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public function getExchangeRate(string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        if (!isset(self::EXCHANGE_RATES[$fromCurrency][$toCurrency])) {
            throw new \InvalidArgumentException("Exchange rate from {$fromCurrency} to {$toCurrency} not available");
        }

        return self::EXCHANGE_RATES[$fromCurrency][$toCurrency];
    }

    /**
     * Update exchange rates (placeholder for future API integration)
     *
     * @param array $rates
     * @return void
     */
    public function updateExchangeRates(array $rates): void
    {
        // TODO: Implement API integration for real-time exchange rates
        // This could integrate with services like:
        // - Fixer.io
        // - ExchangeRate-API
        // - Open Exchange Rates
        // - Currency Layer
    }

    /**
     * Get currency options for forms
     *
     * @return array
     */
    public function getCurrencyOptions(): array
    {
        $options = [];
        foreach (self::SUPPORTED_CURRENCIES as $currency) {
            $options[$currency] = self::CURRENCY_SYMBOLS[$currency] . ' ' . self::CURRENCY_NAMES[$currency];
        }
        return $options;
    }

    /**
     * Calculate earnings in multiple currencies
     *
     * @param float $amount
     * @param string $baseCurrency
     * @return array
     */
    public function calculateMultiCurrency(float $amount, string $baseCurrency): array
    {
        $result = [];
        foreach (self::SUPPORTED_CURRENCIES as $currency) {
            $result[$currency] = $this->convert($amount, $baseCurrency, $currency);
        }
        return $result;
    }
}
