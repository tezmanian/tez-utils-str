<?php

declare(strict_types=1);

namespace Tez\Utils\Str;

final class Random
{
    /**
     * Returns a cryptographically secure hex string of $length characters.
     *
     * $length must be a positive integer. Odd values are supported —
     * the required bytes are rounded up and the result is trimmed to $length.
     *
     * @throws \InvalidArgumentException
     */
    public static function token(int $length = 32): string
    {
        if ($length < 1) {
            throw new \InvalidArgumentException('$length must be >= 1.');
        }

        $bytes = (int) ceil($length / 2);
        assert($bytes >= 1);

        return substr(bin2hex(random_bytes($bytes)), 0, $length);
    }

    /**
     * Returns a cryptographically secure random string drawn from [a-zA-Z0-9].
     *
     * Uses rejection sampling to guarantee uniform distribution (no modulo bias).
     *
     * @throws \InvalidArgumentException
     */
    public static function alphanumeric(int $length = 32): string
    {
        return self::charset($length, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
    }

    /**
     * Returns a cryptographically secure random string drawn from $chars.
     *
     * Uses rejection sampling to guarantee uniform distribution regardless of
     * alphabet size.
     *
     * @throws \InvalidArgumentException
     */
    public static function charset(int $length, string $chars): string
    {
        if ($length < 1) {
            throw new \InvalidArgumentException('$length must be >= 1.');
        }

        if ($chars === '') {
            throw new \InvalidArgumentException('$chars must not be empty.');
        }

        $charsetLen = strlen($chars);
        $maxByte    = 256 - (256 % $charsetLen);
        $result     = '';

        while (strlen($result) < $length) {
            $byte = ord(random_bytes(1));

            if ($byte >= $maxByte) {
                continue;
            }

            $result .= $chars[$byte % $charsetLen];
        }

        return $result;
    }
}
