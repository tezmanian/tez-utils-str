<?php

declare(strict_types=1);

namespace Tez\Utils\Str;

final class Slugify
{
    /**
     * Transliteration table for common non-ASCII characters.
     * Covers German, French, Spanish, Nordic, and other Western European scripts.
     *
     * @var array<string, string>
     */
    private const TRANSLITERATIONS = [
        // German
        'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue',
        'Ä' => 'ae', 'Ö' => 'oe', 'Ü' => 'ue',
        'ß' => 'ss',
        // French
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'É' => 'e', 'È' => 'e', 'Ê' => 'e', 'Ë' => 'e',
        'à' => 'a', 'â' => 'a', 'á' => 'a', 'ã' => 'a',
        'À' => 'a', 'Â' => 'a', 'Á' => 'a', 'Ã' => 'a',
        'ù' => 'u', 'û' => 'u', 'ú' => 'u',
        'Ù' => 'u', 'Û' => 'u', 'Ú' => 'u',
        'î' => 'i', 'ï' => 'i', 'í' => 'i', 'ì' => 'i',
        'Î' => 'i', 'Ï' => 'i', 'Í' => 'i', 'Ì' => 'i',
        'ô' => 'o', 'ó' => 'o', 'õ' => 'o',
        'Ô' => 'o', 'Ó' => 'o', 'Õ' => 'o',
        'ç' => 'c', 'Ç' => 'c',
        // Spanish
        'ñ' => 'n', 'Ñ' => 'n',
        // Nordic
        'å' => 'a', 'Å' => 'a',
        'æ' => 'ae', 'Æ' => 'ae',
        'ø' => 'o', 'Ø' => 'o',
    ];

    /**
     * Converts $input to a URL-safe slug.
     *
     * Transliterates known non-ASCII characters, lowercases the result,
     * replaces any sequence of non-alphanumeric characters with $separator,
     * and trims leading/trailing separators.
     *
     * Returns an empty string for blank input.
     */
    public static function slug(string $input, string $separator = '-'): string
    {
        $text = strtr($input, self::TRANSLITERATIONS);
        $text = mb_strtolower($text);

        $quotedSep = preg_quote($separator, '/');
        $text      = preg_replace('/[^a-z0-9]+/', $separator, $text) ?? '';
        $text      = preg_replace('/^' . $quotedSep . '+|' . $quotedSep . '+$/', '', $text) ?? '';

        return $text;
    }
}
