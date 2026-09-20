<?php

namespace App\Support;

class Money
{
    /**
     * Currency symbols lookup.
     *
     * @var array<string, string>
     */
    protected const SYMBOLS = [
        'NGN' => '₦',
        'GBP' => '£',
        'USD' => '$',
        'CAD' => 'CA$',
        'EUR' => '€',
    ];

    /**
     * Format minor integer units (kobo/cents) to human-readable string.
     */
    public static function format(int|float|null $amountMinor, string $currency = 'NGN', bool $showCode = false): string
    {
        $amountMinor = (int) ($amountMinor ?? 0);
        $amountMajor = $amountMinor / 100;
        $currency = strtoupper($currency);
        $symbol = self::SYMBOLS[$currency] ?? $currency.' ';

        $formattedNumber = number_format($amountMajor, 2);

        if ($showCode) {
            return sprintf('%s%s %s', $symbol, $formattedNumber, $currency);
        }

        return sprintf('%s%s', $symbol, $formattedNumber);
    }

    /**
     * Get symbol for currency code.
     */
    public static function symbol(string $currency = 'NGN'): string
    {
        return self::SYMBOLS[strtoupper($currency)] ?? $currency;
    }

    /**
     * Convert major unit to minor unit (e.g. 15.50 -> 1550).
     */
    public static function toMinor(float|int $amountMajor): int
    {
        return (int) round($amountMajor * 100);
    }

    /**
     * Convert minor unit to major unit (e.g. 1550 -> 15.50).
     */
    public static function toMajor(int $amountMinor): float
    {
        return round($amountMinor / 100, 2);
    }
}
