@props(['amount', 'currency', 'showSymbol' => true, 'decimals' => 4])

@php
    $currencyService = app(\App\Services\CurrencyService::class);
@endphp

<span class="currency-display" data-amount="{{ $amount }}" data-currency="{{ $currency }}">
    {{ $currencyService->format($amount, $currency, $showSymbol) }}
</span>
