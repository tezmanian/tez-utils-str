<?php

declare(strict_types=1);

namespace Tezmanian\Utils\Str;

final class Truncate
{
    /**
     * Truncates $text to at most $limit characters (including the suffix).
     *
     * With $wordBoundary = true (default) the cut is moved back to the last
     * complete word so no word is split in the middle.
     * With $wordBoundary = false the cut is made at the exact character position.
     *
     * Returns the original string unchanged when mb_strlen($text) <= $limit.
     * Throws \InvalidArgumentException when $limit is shorter than the suffix.
     *
     * @throws \InvalidArgumentException
     */
    public static function chars(
        string $text,
        int $limit,
        string $suffix = '…',
        bool $wordBoundary = true,
    ): string {
        $suffixLen = mb_strlen($suffix);

        if ($limit < $suffixLen) {
            throw new \InvalidArgumentException(
                sprintf('$limit (%d) must be >= suffix length (%d).', $limit, $suffixLen),
            );
        }

        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        $cutLength = $limit - $suffixLen;
        $cut       = mb_substr($text, 0, $cutLength);

        if ($wordBoundary) {
            $nextChar = mb_substr($text, $cutLength, 1);

            if ($nextChar !== '' && $nextChar !== ' ') {
                $lastSpace = mb_strrpos($cut, ' ');

                if ($lastSpace !== false) {
                    $cut = mb_substr($cut, 0, $lastSpace);
                }
            }
        }

        return rtrim($cut) . $suffix;
    }
}
