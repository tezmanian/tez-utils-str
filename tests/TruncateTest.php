<?php

declare(strict_types=1);

namespace Tezmanian\Utils\Tests\Str;

use PHPUnit\Framework\TestCase;
use Tezmanian\Utils\Str\Truncate;

final class TruncateTest extends TestCase
{
    // -------------------------------------------------------------------------
    // Short strings — returned unchanged
    // -------------------------------------------------------------------------

    public function testShortStringReturnedUnchanged(): void
    {
        self::assertSame('Short', Truncate::chars('Short', 20));
    }

    public function testStringExactlyAtLimitReturnedUnchanged(): void
    {
        self::assertSame('12345', Truncate::chars('12345', 5));
    }

    // -------------------------------------------------------------------------
    // Word boundary (default)
    // -------------------------------------------------------------------------

    public function testTruncatesAtWordBoundary(): void
    {
        // cut position falls on a space → keep up to the space
        self::assertSame(
            'The quick brown fox…',
            Truncate::chars('The quick brown fox jumps over the lazy dog', 20),
        );
    }

    public function testTruncatesBackToLastWordWhenCutIsInsideWord(): void
    {
        // limit=15, suffix=1 → cut at 14 chars → 'The quick brow' → trims to 'The quick'
        self::assertSame('The quick…', Truncate::chars('The quick brown fox', 15));
    }

    public function testWordBoundaryWithNoSpaceInCutKeepsRawCut(): void
    {
        self::assertSame('Hello…', Truncate::chars('Hellothere', 6));
    }

    // -------------------------------------------------------------------------
    // Exact cut (wordBoundary = false)
    // -------------------------------------------------------------------------

    public function testExactCutIgnoresWordBoundary(): void
    {
        self::assertSame(
            'The quick brown fox…',
            Truncate::chars('The quick brown fox jumps', 20, wordBoundary: false),
        );
    }

    public function testExactCutSplitsWord(): void
    {
        self::assertSame('The quick bro…', Truncate::chars('The quick brown fox', 14, wordBoundary: false));
    }

    // -------------------------------------------------------------------------
    // Custom suffix
    // -------------------------------------------------------------------------

    public function testCustomSuffix(): void
    {
        // suffix=' [...]' (6 chars), limit=22 → cut at 16 → 'The quick brown ' → trims to 'The quick brown'
        self::assertSame(
            'The quick brown [...]',
            Truncate::chars('The quick brown fox jumps', 22, suffix: ' [...]'),
        );
    }

    public function testEmptySuffix(): void
    {
        self::assertSame('The quick', Truncate::chars('The quick brown fox', 9, suffix: ''));
    }

    // -------------------------------------------------------------------------
    // Multibyte
    // -------------------------------------------------------------------------

    public function testMultibyteCharactersCountedCorrectly(): void
    {
        // 'Ärger mit Umlauten' — each char counts as 1 even though ä is multibyte
        self::assertSame('Ärger…', Truncate::chars('Ärger mit Umlauten', 6));
    }

    // -------------------------------------------------------------------------
    // Edge cases / exceptions
    // -------------------------------------------------------------------------

    public function testThrowsWhenLimitShorterThanSuffix(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Truncate::chars('Hello', 2, suffix: '...');
    }

    public function testLimitEqualToSuffixLengthReturnsEmptySuffix(): void
    {
        // limit=3, suffix='...' (3 chars) → cutLength=0 → '' + '...'
        self::assertSame('...', Truncate::chars('Hello world', 3, suffix: '...'));
    }
}
