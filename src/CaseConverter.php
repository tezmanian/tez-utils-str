<?php

declare(strict_types=1);

namespace Tez\Utils\Str;

final class CaseConverter
{
    /** Converts to camelCase. */
    public static function toCamel(string $input): string
    {
        $words = self::words($input);

        if ($words === []) {
            return '';
        }

        return $words[0] . implode('', array_map('ucfirst', \array_slice($words, 1)));
    }

    /** Converts to PascalCase. */
    public static function toPascal(string $input): string
    {
        return implode('', array_map('ucfirst', self::words($input)));
    }

    /** Converts to snake_case. */
    public static function toSnake(string $input): string
    {
        return implode('_', self::words($input));
    }

    /** Converts to kebab-case. */
    public static function toKebab(string $input): string
    {
        return implode('-', self::words($input));
    }

    /** Converts to SCREAMING_SNAKE_CASE. */
    public static function toScreamingSnake(string $input): string
    {
        return mb_strtoupper(implode('_', self::words($input)));
    }

    /**
     * Splits any supported format into lowercase words.
     *
     * Handles: camelCase, PascalCase, snake_case, kebab-case, SCREAMING_SNAKE_CASE.
     *
     * @return list<string>
     */
    private static function words(string $input): array
    {
        // Insert underscore before transitions from lowercase/digit to uppercase
        $input = preg_replace('/([a-z\d])([A-Z])/', '$1_$2', $input) ?? $input;

        // Insert underscore before transitions from a run of uppercase into mixed (e.g. HTMLParser → HTML_Parser)
        $input = preg_replace('/([A-Z]+)([A-Z][a-z])/', '$1_$2', $input) ?? $input;

        // Split on underscores, hyphens, and whitespace
        $words = preg_split('/[_\-\s]+/', $input) ?: [];

        return array_values(array_filter(
            array_map('mb_strtolower', $words),
            static fn(string $w): bool => $w !== '',
        ));
    }
}
