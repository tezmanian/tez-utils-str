<?php

declare(strict_types=1);

namespace Tezmanian\Utils\Str;

final class Interpolate
{
    /**
     * Replaces placeholders in a template string with values from an array.
     *
     * Supports dot-notation for nested keys: "{user.name}" → $data['user']['name'].
     * By default throws \InvalidArgumentException for missing keys.
     * Pass a $fallback string to use it instead of throwing.
     *
     * @param array<string, mixed> $data
     * @throws \InvalidArgumentException when a key is missing and no fallback is set
     */
    public static function render(
        string $template,
        array $data,
        string $prefix = '{',
        string $suffix = '}',
        ?string $fallback = null,
    ): string {
        $prefixQ = preg_quote($prefix, '/');
        $suffixQ = preg_quote($suffix, '/');

        $keyPattern = $suffix !== ''
            ? '[^' . $prefixQ . $suffixQ . ']+'
            : '[a-zA-Z0-9_.]+';

        return (string) preg_replace_callback(
            '/' . $prefixQ . '(' . $keyPattern . ')' . $suffixQ . '/',
            static function (array $matches) use ($data, $fallback): string {
                $key   = $matches[1];
                $value = self::resolve($key, $data);

                if ($value === null) {
                    if ($fallback !== null) {
                        return $fallback;
                    }

                    throw new \InvalidArgumentException(
                        sprintf('Missing key "%s" in template data.', $key),
                    );
                }

                return is_scalar($value) ? (string) $value : '';
            },
            $template,
        );
    }

    /**
     * Resolves a dot-notation key against a nested array.
     *
     * @param array<string, mixed> $data
     */
    private static function resolve(string $key, array $data): mixed
    {
        $segments = explode('.', $key);
        $current  = $data;

        foreach ($segments as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return null;
            }

            $current = $current[$segment];
        }

        return $current;
    }
}
