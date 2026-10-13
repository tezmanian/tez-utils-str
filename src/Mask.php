<?php

declare(strict_types=1);

namespace Tezmanian\Utils\Str;

final class Mask
{
    /**
     * Masks the local part of an email address, keeping only the first character.
     *
     * Example: "test@example.com" → "t***@example.com"
     */
    public static function email(string $email): string
    {
        $atPos = strpos($email, '@');

        if ($email === '' || $atPos === false || $atPos === 0) {
            return $email;
        }

        return substr($email, 0, 1) . '***' . substr($email, $atPos);
    }

    /**
     * Masks a credit card number, keeping only the last 4 digits.
     *
     * Example: "4111111111111111" → "**** **** **** 1111"
     */
    public static function creditCard(string $number): string
    {
        $digits = preg_replace('/\D/', '', $number);

        if ($digits === null || strlen($digits) < 4) {
            return $number;
        }

        $last4 = substr($digits, -4);

        return '**** **** **** ' . $last4;
    }

    /**
     * Masks an IBAN, keeping the country code + check digits (first 4 chars)
     * and the last 2 characters. The result is formatted in groups of 4.
     *
     * Example: "DE89370400440532013000" → "DE89 **** **** **** **** 00"
     */
    public static function iban(string $iban): string
    {
        $clean = str_replace(' ', '', $iban);

        if (strlen($clean) < 7) {
            return $iban;
        }

        $visible = 4;
        $tail    = 2;
        $masked  = substr($clean, 0, $visible)
            . str_repeat('*', strlen($clean) - $visible - $tail)
            . substr($clean, -$tail);

        return implode(' ', str_split($masked, 4));
    }

    /**
     * Masks a string with a configurable number of visible characters at the start and end.
     *
     * Example: Mask::custom('MySuperSecret', start: 2, end: 2) → "My*********et"
     *
     * @param non-empty-string $char
     */
    public static function custom(
        string $value,
        int $start,
        int $end,
        string $char = '*',
    ): string {
        $length = strlen($value);

        if ($value === '' || $start + $end >= $length) {
            return $value;
        }

        $maskLen = $length - $start - $end;

        return substr($value, 0, $start)
            . str_repeat($char, $maskLen)
            . ($end > 0 ? substr($value, -$end) : '');
    }
}
